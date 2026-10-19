<?php

namespace Tests\Feature;

use App\Services\ProjectBackup;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;
use ZipArchive;

class ProjectBackupTest extends TestCase
{
    private string $directory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->directory = storage_path('framework/testing/backup-'.Str::uuid());
        File::ensureDirectoryExists($this->directory);
        File::put($this->directory.'/test.sqlite', '');
        config([
            'database.default' => 'backup_test',
            'database.connections.backup_test' => ['driver' => 'sqlite', 'database' => $this->directory.'/test.sqlite', 'prefix' => '', 'foreign_key_constraints' => true],
            'backups.path' => $this->directory.'/archives',
        ]);
        Schema::create('notes', function (Blueprint $table) { $table->id(); $table->string('title'); });
        DB::table('notes')->insert(['title' => 'Original']);
        Storage::fake('public');
        Storage::fake('local');
    }

    protected function tearDown(): void
    {
        DB::purge('backup_test');
        File::deleteDirectory($this->directory);
        parent::tearDown();
    }

    public function test_backup_and_restore_recover_database_photos_and_private_proofs(): void
    {
        Storage::disk('public')->put('products/photo.webp', 'photo-original');
        Storage::disk('local')->put('payment-proofs/proof.pdf', 'private-original');
        Storage::disk('local')->put('.env', 'never-archive-secrets');
        Storage::disk('local')->put('backups/older.zip', 'no-recursion');
        $backups = app(ProjectBackup::class);
        $archive = $backups->create();
        $manifest = $backups->verify($archive);
        $this->assertCount(3, $manifest['files']);
        $this->assertArrayNotHasKey('private/.env', $manifest['files']);
        $this->artisan('vendefacil:backup-verify', ['archive' => $archive])->assertSuccessful();
        DB::table('notes')->update(['title' => 'Modified']);
        Storage::disk('public')->delete('products/photo.webp');
        Storage::disk('local')->put('payment-proofs/proof.pdf', 'changed');
        $this->artisan('vendefacil:restore', ['archive' => $archive])->assertFailed();
        $this->assertSame('Modified', DB::table('notes')->value('title'));
        $this->artisan('vendefacil:restore', ['archive' => $archive, '--force' => true])->assertSuccessful();
        $this->assertSame('Original', DB::table('notes')->value('title'));
        $this->assertSame('photo-original', Storage::disk('public')->get('products/photo.webp'));
        $this->assertSame('private-original', Storage::disk('local')->get('payment-proofs/proof.pdf'));
        $this->assertCount(2, File::glob($this->directory.'/archives/*.zip'));
    }

    public function test_corrupted_backup_is_rejected_before_any_database_change(): void
    {
        $archive = app(ProjectBackup::class)->create();
        $zip = new ZipArchive;
        $zip->open($archive);
        $zip->addFromString('database.sqlite', 'tampered');
        $zip->close();
        $this->artisan('vendefacil:restore', ['archive' => $archive, '--force' => true])->assertFailed();
        $this->assertSame('Original', DB::table('notes')->value('title'));
        $this->assertCount(1, File::glob($this->directory.'/archives/*.zip'));
    }

    public function test_archive_path_traversal_is_rejected(): void
    {
        $archive = $this->directory.'/malicious.zip';
        $zip = new ZipArchive;
        $zip->open($archive, ZipArchive::CREATE);
        $zip->addFromString('database.sqlite', 'data');
        $zip->addFromString('public/../../escape.txt', 'escape');
        $zip->addFromString('manifest.json', json_encode(['version' => 1, 'driver' => 'sqlite', 'files' => [
            'database.sqlite' => hash('sha256', 'data'), 'public/../../escape.txt' => hash('sha256', 'escape'),
        ]]));
        $zip->close();
        $this->artisan('vendefacil:backup-verify', ['archive' => $archive])->assertFailed();
        $this->assertFileDoesNotExist($this->directory.'/escape.txt');
    }

    public function test_retention_only_removes_expired_project_archives(): void
    {
        File::ensureDirectoryExists(config('backups.path'));
        $old = config('backups.path').'/vendefacil-old.zip';
        $other = config('backups.path').'/manual.zip';
        File::put($old, 'old');
        File::put($other, 'keep');
        touch($old, now()->subDays(20)->getTimestamp());
        $this->assertSame(1, app(ProjectBackup::class)->prune());
        $this->assertFileExists($other);
    }
}
