<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LaunchCheck extends Command
{
    protected $signature = 'vendefacil:launch-check {--local : Revisa desarrollo sin exigir configuracion de produccion}';
    protected $description = 'Verifica la configuracion necesaria para publicar sin mostrar secretos.';

    public function handle(): int
    {
        $local = (bool) $this->option('local');
        $checks = [
            'Entorno apropiado' => $local ? app()->environment(['local', 'testing']) : app()->isProduction(),
            'Depuracion desactivada' => ! config('app.debug'),
            'URL de la instalacion' => $local ? (bool) filter_var(config('app.url'), FILTER_VALIDATE_URL) : str_starts_with(config('app.url'), 'https://'),
            'Clave de cifrado configurada' => filled(config('app.key')),
            'Cookies apropiadas para la URL' => ($local && ! str_starts_with(config('app.url'), 'https://')) || (bool) config('session.secure'),
            'Cookies HTTP only' => (bool) config('session.http_only'),
            'Sesion cifrada' => (bool) config('session.encrypt'),
            'Correo real configurado' => config('mail.default') === 'smtp'
                && filled(config('mail.mailers.smtp.host'))
                && ! in_array(config('mail.mailers.smtp.host'), ['127.0.0.1', 'localhost'], true)
                && filled(config('mail.mailers.smtp.username'))
                && filled(config('mail.mailers.smtp.password'))
                && filter_var(config('mail.from.address'), FILTER_VALIDATE_EMAIL)
                && ! str_ends_with(config('mail.from.address'), '@example.com'),
            'Interfaz adecuada al entorno' => $local
                ? (is_file(public_path('build/manifest.json')) || is_file(public_path('hot')))
                : (is_file(public_path('build/manifest.json')) && ! is_file(public_path('hot'))),
            'Comprobantes fuera del disco publico' => empty(Storage::disk('public')->allFiles('payment-proofs')),
            'Extension ZIP para respaldos' => class_exists(\ZipArchive::class),
            'Respaldo privado reciente' => collect(\Illuminate\Support\Facades\File::glob(config('backups.path').'/vendefacil-*.zip'))
                ->contains(fn ($path) => filemtime($path) >= now()->subDays(2)->getTimestamp()),
        ];
        try { DB::select('SELECT 1'); $checks['Conexion de base de datos'] = true; }
        catch (\Throwable) { $checks['Conexion de base de datos'] = false; }
        foreach ($checks as $name => $ok) { $this->line(($ok ? '[OK] ' : '[PENDIENTE] ').$name); }
        return in_array(false, $checks, true) ? self::FAILURE : self::SUCCESS;
    }
}
