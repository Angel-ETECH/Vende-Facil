<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LaunchCheck extends Command
{
    protected $signature = 'vendefacil:launch-check';
    protected $description = 'Verifica la configuracion necesaria para publicar sin mostrar secretos.';

    public function handle(): int
    {
        $checks = [
            'Entorno de produccion' => app()->isProduction(),
            'Depuracion desactivada' => ! config('app.debug'),
            'URL HTTPS' => str_starts_with(config('app.url'), 'https://'),
            'Clave de cifrado configurada' => filled(config('app.key')),
            'Cookies seguras' => (bool) config('session.secure'),
            'Cookies HTTP only' => (bool) config('session.http_only'),
            'Sesion cifrada' => (bool) config('session.encrypt'),
            'Correo real configurado' => config('mail.default') === 'smtp'
                && filled(config('mail.mailers.smtp.host'))
                && ! in_array(config('mail.mailers.smtp.host'), ['127.0.0.1', 'localhost'], true)
                && filled(config('mail.mailers.smtp.username'))
                && filled(config('mail.mailers.smtp.password'))
                && filter_var(config('mail.from.address'), FILTER_VALIDATE_EMAIL)
                && ! str_ends_with(config('mail.from.address'), '@example.com'),
            'Build de produccion disponible' => is_file(public_path('build/manifest.json')) && ! is_file(public_path('hot')),
            'Comprobantes fuera del disco publico' => empty(Storage::disk('public')->allFiles('payment-proofs')),
        ];
        try { DB::select('SELECT 1'); $checks['Conexion de base de datos'] = true; }
        catch (\Throwable) { $checks['Conexion de base de datos'] = false; }
        foreach ($checks as $name => $ok) { $this->line(($ok ? '[OK] ' : '[PENDIENTE] ').$name); }
        return in_array(false, $checks, true) ? self::FAILURE : self::SUCCESS;
    }
}
