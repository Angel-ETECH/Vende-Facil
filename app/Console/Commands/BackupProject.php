<?php

namespace App\Console\Commands;

use App\Services\ProjectBackup;
use Illuminate\Console\Command;

class BackupProject extends Command
{
    protected $signature = 'vendefacil:backup';
    protected $description = 'Respalda la base de datos, imagenes y comprobantes en un ZIP privado verificado.';

    public function handle(ProjectBackup $backups): int
    {
        try {
            $path = $backups->create();
            $this->info('Respaldo creado y verificado: '.$path);
            $this->line('Respaldos antiguos retirados: '.$backups->prune());
            return self::SUCCESS;
        } catch (\Throwable $error) {
            report($error);
            $this->error('No se completo el respaldo. Revisa el registro privado de Laravel y la configuracion de la base.');
            return self::FAILURE;
        }
    }
}
