<?php

namespace App\Console\Commands;

use App\Services\ProjectBackup;
use Illuminate\Console\Command;

class RestoreProject extends Command
{
    protected $signature = 'vendefacil:restore {archive : Ruta del ZIP privado} {--force : Autoriza reemplazar la base configurada}';
    protected $description = 'Verifica un respaldo y lo restaura, guardando antes una copia del estado actual.';

    public function handle(ProjectBackup $backups): int
    {
        if (! $this->option('force') || (app()->isProduction() && ! app()->isDownForMaintenance())) {
            $this->error('Usa --force. En produccion primero activa mantenimiento con php artisan down.');
            return self::FAILURE;
        }
        try {
            $backups->verify($this->argument('archive'));
            $safety = $backups->create();
            $this->info('Copia previa conservada: '.$safety);
            $backups->restore($this->argument('archive'));
            $this->info('Restauracion completada. El modo mantenimiento no se ha modificado.');
            return self::SUCCESS;
        } catch (\Throwable $error) {
            report($error);
            $this->error('La restauracion no se completo. Conserva el mantenimiento y revisa el registro privado.');
            return self::FAILURE;
        }
    }
}
