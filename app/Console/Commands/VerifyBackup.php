<?php

namespace App\Console\Commands;

use App\Services\ProjectBackup;
use Illuminate\Console\Command;

class VerifyBackup extends Command
{
    protected $signature = 'vendefacil:backup-verify {archive : Ruta del ZIP privado}';
    protected $description = 'Comprueba los archivos y las sumas SHA-256 de un respaldo sin restaurarlo.';

    public function handle(ProjectBackup $backups): int
    {
        try {
            $manifest = $backups->verify($this->argument('archive'));
            $this->info('Respaldo integro: '.count($manifest['files']).' archivos, motor '.$manifest['driver'].'.');
            return self::SUCCESS;
        } catch (\Throwable $error) {
            $this->error('El archivo no es un respaldo integro de VendeFacil. No se ha restaurado nada.');
            return self::FAILURE;
        }
    }
}
