<?php

namespace Tests\Feature;

use App\Services\ProjectBackup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class MySqlBackupIntegrationTest extends TestCase
{
    public function test_real_mysql_dump_restores_an_isolated_database(): void
    {
        if (getenv('VENDEFACIL_MYSQL_BACKUP_TEST') !== '1') {
            $this->markTestSkipped('Activa VENDEFACIL_MYSQL_BACKUP_TEST=1 para probar MySQL en una base temporal.');
        }
        $database = 'vendefacil_backup_test_'.bin2hex(random_bytes(6));
        $connection = array_merge(config('database.connections.mysql'), ['database' => '', 'url' => null]);
        config(['database.connections.backup_admin' => $connection]);
        $directory = storage_path('framework/testing/mysql-backup-'.Str::uuid());
        DB::connection('backup_admin')->statement('CREATE DATABASE `'.$database.'`');
        try {
            config([
                'database.connections.backup_mysql' => array_merge($connection, ['database' => $database]),
                'database.default' => 'backup_mysql', 'backups.path' => $directory.'/archives',
            ]);
            Storage::fake('public');
            Storage::fake('local');
            DB::statement('CREATE TABLE backup_notes (id INT PRIMARY KEY, title VARCHAR(100))');
            DB::table('backup_notes')->insert(['id' => 1, 'title' => 'Before backup']);
            Storage::disk('local')->put('payment-proofs/sample.pdf', 'private-example');
            $backups = app(ProjectBackup::class);
            $archive = $backups->create();
            DB::table('backup_notes')->update(['title' => 'After backup']);
            Storage::disk('local')->delete('payment-proofs/sample.pdf');
            $backups->restore($archive);
            $this->assertSame('Before backup', DB::table('backup_notes')->value('title'));
            $this->assertSame('private-example', Storage::disk('local')->get('payment-proofs/sample.pdf'));
        } finally {
            DB::purge('backup_mysql');
            DB::connection('backup_admin')->statement('DROP DATABASE `'.$database.'`');
            DB::purge('backup_admin');
            File::deleteDirectory($directory);
        }
    }
}
