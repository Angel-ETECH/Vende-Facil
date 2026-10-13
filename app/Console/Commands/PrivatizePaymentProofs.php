<?php

namespace App\Console\Commands;

use App\Models\PaymentProof;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PrivatizePaymentProofs extends Command
{
    protected $signature = 'vendefacil:privatize-proofs';
    protected $description = 'Mueve comprobantes antiguos al almacenamiento privado, verificando cada copia.';

    public function handle(): int
    {
        $public = Storage::disk('public');
        $private = Storage::disk('local');
        $missing = 0;
        foreach (PaymentProof::orderBy('id')->cursor() as $proof) {
            $path = $proof->proof_path;
            if (! str_starts_with($path, 'payment-proofs/') || str_contains($path, '..') || str_contains($path, '\\')) {
                $this->error('Ruta invalida en comprobante '.$proof->id); $missing++; continue;
            }
            if ($public->exists($path)) {
                $content = $public->get($path);
                if (! $private->exists($path) && ! $private->put($path, $content)) {
                    $this->error('No se pudo copiar comprobante '.$proof->id); $missing++; continue;
                }
                if (! hash_equals(hash('sha256', $content), hash('sha256', $private->get($path)))) {
                    $this->error('La copia no coincide en comprobante '.$proof->id); $missing++; continue;
                }
                if (! $public->delete($path)) {
                    $this->error('No se pudo retirar la copia publica '.$proof->id); $missing++; continue;
                }
            }
            if (! $private->exists($path)) {
                $this->error('Archivo ausente en comprobante '.$proof->id); $missing++; continue;
            }
            $hash = hash('sha256', $private->get($path));
            if (! $proof->proof_hash && ! PaymentProof::where('proof_hash', $hash)->whereKeyNot($proof->id)->exists()) {
                $proof->forceFill(['proof_hash' => $hash])->save();
            }
            $reference = preg_replace('/\s+/', '', mb_strtoupper(trim($proof->reference ?? '')));
            $key = $reference !== '' ? hash('sha256', $proof->method.'|'.$reference) : null;
            if ($key && ! $proof->reference_key && ! PaymentProof::where('reference_key', $key)->whereKeyNot($proof->id)->exists()) {
                $proof->forceFill(['reference_key' => $key])->save();
            }
        }
        $this->info('Comprobantes revisados. Archivos con incidencias: '.$missing);
        return $missing ? self::FAILURE : self::SUCCESS;
    }
}
