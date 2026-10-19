<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Process\Process;
use ZipArchive;

class ProjectBackup
{
    public function create(): string
    {
        $work = $this->workspace();
        $archive = rtrim(config('backups.path'), '/\\').'/vendefacil-'.now()->format('Ymd-His').'-'.Str::uuid().'.zip';
        $zip = new ZipArchive;
        try {
            File::ensureDirectoryExists(dirname($archive), 0700);
            $driver = DB::connection()->getDriverName();
            $database = $driver === 'sqlite' ? 'database.sqlite' : 'database.sql';
            if ($driver === 'sqlite') {
                DB::connection()->getPdo()->exec('VACUUM INTO '.DB::connection()->getPdo()->quote($work.'/'.$database));
            } elseif ($driver === 'mysql') {
                $this->mysqlProcess('mysqldump', [
                    '--single-transaction', '--quick', '--skip-lock-tables', '--hex-blob',
                    '--result-file='.$work.'/'.$database,
                ], $work);
            } else {
                throw new RuntimeException('Los respaldos admiten MySQL y SQLite.');
            }
            if ($zip->open($archive, ZipArchive::CREATE | ZipArchive::EXCL) !== true) {
                throw new RuntimeException('No se pudo crear el archivo de respaldo.');
            }
            $files = [$database => hash_file('sha256', $work.'/'.$database)];
            if (! $zip->addFile($work.'/'.$database, $database)) {
                throw new RuntimeException('No se pudo agregar la base de datos.');
            }
            foreach (['public' => 'public', 'local' => 'private'] as $disk => $prefix) {
                $root = Storage::disk($disk)->path('');
                if (! is_dir($root)) { continue; }
                foreach (File::allFiles($root) as $file) {
                    $relative = str_replace('\\', '/', $file->getRelativePathname());
                    if ($file->isLink() || str_starts_with($relative, 'backups/') || str_starts_with($relative, 'backup-work/') || str_starts_with($file->getFilename(), '.')) {
                        continue;
                    }
                    $name = $prefix.'/'.$relative;
                    $files[$name] = hash_file('sha256', $file->getPathname());
                    if (! $zip->addFile($file->getPathname(), $name)) {
                        throw new RuntimeException('No se pudo agregar un archivo al respaldo.');
                    }
                }
            }
            $zip->addFromString('manifest.json', json_encode([
                'version' => 1, 'created_at' => now()->toIso8601String(),
                'driver' => $driver, 'files' => $files,
            ], JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));
            if (! $zip->close()) { throw new RuntimeException('No se pudo finalizar el respaldo.'); }
            @chmod($archive, 0600);
            $this->verify($archive);

            return $archive;
        } catch (\Throwable $error) {
            if ($zip->status === ZipArchive::ER_OK) { try { $zip->close(); } catch (\Throwable) {} }
            File::delete($archive);
            throw $error;
        } finally {
            File::deleteDirectory($work);
        }
    }

    public function verify(string $archive): array
    {
        $zip = new ZipArchive;
        if ($zip->open($archive, ZipArchive::RDONLY) !== true) {
            throw new RuntimeException('El respaldo no es un ZIP valido.');
        }
        try {
            $raw = $zip->getFromName('manifest.json', 1048576);
            $manifest = is_string($raw) ? json_decode($raw, true, 512, JSON_THROW_ON_ERROR) : null;
            if (! is_array($manifest) || ($manifest['version'] ?? null) !== 1 || ! in_array($manifest['driver'] ?? null, ['sqlite', 'mysql'], true) || ! is_array($manifest['files'] ?? null)) {
                throw new RuntimeException('El respaldo no contiene un manifiesto compatible.');
            }
            $database = $manifest['driver'] === 'sqlite' ? 'database.sqlite' : 'database.sql';
            if (! isset($manifest['files'][$database]) || $zip->numFiles !== count($manifest['files']) + 1) {
                throw new RuntimeException('El respaldo esta incompleto o contiene archivos inesperados.');
            }
            $seen = [];
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = $zip->getNameIndex($i);
                if (! is_string($name) || isset($seen[$name])) { throw new RuntimeException('El ZIP contiene entradas duplicadas.'); }
                $seen[$name] = true;
                if ($name === 'manifest.json') { continue; }
                if (! $this->safeEntry($name, $database) || ! isset($manifest['files'][$name])) {
                    throw new RuntimeException('El ZIP contiene una ruta no permitida.');
                }
                $stream = $zip->getStream($name);
                if ($stream === false) { throw new RuntimeException('No se pudo leer un archivo del ZIP.'); }
                try {
                    $hash = hash_init('sha256');
                    hash_update_stream($hash, $stream);
                    if (! hash_equals((string) $manifest['files'][$name], hash_final($hash))) {
                        throw new RuntimeException('La integridad del respaldo no coincide.');
                    }
                } finally { fclose($stream); }
            }

            return $manifest;
        } finally { $zip->close(); }
    }

    public function restore(string $archive): void
    {
        $manifest = $this->verify($archive);
        if ($manifest['driver'] !== DB::connection()->getDriverName()) {
            throw new RuntimeException('El respaldo y la base configurada deben usar el mismo motor.');
        }
        $work = $this->workspace();
        $zip = new ZipArchive;
        $zip->open($archive, ZipArchive::RDONLY);
        try {
            // Extract only validated names; ZipArchive::extractTo would also accept hostile paths.
            foreach ($manifest['files'] as $name => $hash) {
                $target = $work.'/'.$name;
                File::ensureDirectoryExists(dirname($target), 0700);
                $input = $zip->getStream($name);
                $output = fopen($target, 'xb');
                if ($input === false || $output === false) { throw new RuntimeException('No se pudo preparar la restauracion.'); }
                try {
                    if (stream_copy_to_stream($input, $output) === false) { throw new RuntimeException('No se pudo extraer un archivo.'); }
                } finally { fclose($input); fclose($output); }
            }
            foreach (array_keys($manifest['files']) as $name) {
                if (! str_contains($name, '/')) { continue; }
                [$prefix, $relative] = explode('/', $name, 2);
                $target = Storage::disk($prefix === 'public' ? 'public' : 'local')->path($relative);
                if (is_link($target) || $this->hasLinkedParent($target)) {
                    throw new RuntimeException('No se puede restaurar sobre un enlace simbolico.');
                }
            }
            if ($manifest['driver'] === 'sqlite') {
                $destination = DB::connection()->getDatabaseName();
                if ($destination === ':memory:') { throw new RuntimeException('La restauracion requiere una base SQLite en disco.'); }
                $pdo = new \PDO('sqlite:'.$work.'/database.sqlite');
                if ($pdo->query('PRAGMA integrity_check')->fetchColumn() !== 'ok') {
                    throw new RuntimeException('La copia SQLite no paso su comprobacion de integridad.');
                }
                $pdo = null;
                DB::connection()->getPdo()->exec('PRAGMA wal_checkpoint(TRUNCATE)');
                DB::purge();
                if (! File::copy($work.'/database.sqlite', $destination)) {
                    throw new RuntimeException('No se pudo restaurar la base SQLite.');
                }
            } else {
                $this->mysqlProcess('mysql', [], $work, $work.'/database.sql');
                DB::purge();
            }
            foreach ($manifest['files'] as $name => $hash) {
                if (! str_contains($name, '/')) { continue; }
                [$prefix, $relative] = explode('/', $name, 2);
                $target = Storage::disk($prefix === 'public' ? 'public' : 'local')->path($relative);
                if (is_link($target) || $this->hasLinkedParent($target)) {
                    throw new RuntimeException('No se puede restaurar sobre un enlace simbolico.');
                }
                File::ensureDirectoryExists(dirname($target), 0700);
                if (! File::copy($work.'/'.$name, $target)) { throw new RuntimeException('No se pudo restaurar un archivo.'); }
            }
        } finally {
            $zip->close();
            File::deleteDirectory($work);
        }
    }

    public function prune(): int
    {
        $removed = 0;
        foreach (File::glob(config('backups.path').'/vendefacil-*.zip') as $file) {
            if (! is_link($file) && filemtime($file) < now()->subDays(max(1, config('backups.keep_days')))->getTimestamp()) {
                $removed += (int) File::delete($file);
            }
        }
        return $removed;
    }

    private function workspace(): string
    {
        $path = dirname(config('backups.path')).'/backup-work/'.Str::uuid();
        File::ensureDirectoryExists($path, 0700);
        return $path;
    }

    private function safeEntry(string $name, string $database): bool
    {
        if ($name === $database) { return true; }
        if (! preg_match('~^(public|private)/~', $name) || str_contains($name, '\\') || str_contains($name, ':') || str_contains($name, "\0")) { return false; }
        $parts = explode('/', $name);
        foreach ($parts as $part) { if ($part === '' || str_starts_with($part, '.')) { return false; } }
        return ! in_array($parts[1], ['backups', 'backup-work'], true);
    }

    private function hasLinkedParent(string $path): bool
    {
        for ($parent = dirname($path); dirname($parent) !== $parent; $parent = dirname($parent)) {
            if (is_link($parent)) { return true; }
        }
        return false;
    }

    private function mysqlProcess(string $binary, array $arguments, string $work, ?string $input = null): void
    {
        $connection = DB::connection()->getConfig();
        $options = ['host' => $connection['host'], 'port' => $connection['port'], 'user' => $connection['username'], 'password' => $connection['password'] ?? '', 'default-character-set' => 'utf8mb4'];
        if (! empty($connection['unix_socket'])) { $options['socket'] = $connection['unix_socket']; }
        if (! empty($connection['options'][\PDO::MYSQL_ATTR_SSL_CA])) {
            $options['ssl-ca'] = $connection['options'][\PDO::MYSQL_ATTR_SSL_CA];
            $options['ssl-verify-server-cert'] = '1';
        }
        $contents = "[client]\n";
        foreach ($options as $name => $value) {
            $contents .= $name.'="'.str_replace(["\\", '"', "\n", "\r"], ['\\\\', '\\"', '\\n', '\\r'], (string) $value)."\"\n";
        }
        $credentials = $work.'/mysql-client.cnf';
        File::put($credentials, $contents);
        @chmod($credentials, 0600);
        $process = new Process(array_merge([config('backups.'.$binary), '--defaults-extra-file='.$credentials], $arguments, ['--', $connection['database']]));
        $process->setTimeout(300);
        $stream = $input ? fopen($input, 'rb') : null;
        try {
            if ($stream) { $process->setInput($stream); }
            $process->mustRun();
        } finally {
            if (is_resource($stream)) { fclose($stream); }
            File::delete($credentials);
        }
    }
}
