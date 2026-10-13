<?php

namespace App\Http\Controllers;

use App\Models\PaymentProof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Models\Store;
use App\Models\User;
use App\Notifications\MarketplaceNotice;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class PaymentProofController extends Controller
{
    public function index()
    {
        $store = Auth::user()->stores()->first();

        if (! $store) {
            return redirect()
                ->route('stores.create')
                ->with('error', 'Primero debes crear una tienda.');
        }

        $paymentProofs = $store->paymentProofs()
            ->latest()
            ->get();

        $pendingPaymentProof = $paymentProofs
            ->firstWhere('status', PaymentProof::STATUS_PENDING);

        $productLimit = config('vendefacil.free_product_limit');
        $productCount = $store->products()->count();
        $remainingProductSlots = $store->remainingFreeProducts($productCount);
        $planPrice = config('vendefacil.unlimited_products_price');
        $paymentNumber = config('vendefacil.payment_number');
        $paymentWhatsappUrl = $this->paymentWhatsappUrl($store);

        return view('payments.index', compact(
            'store',
            'paymentProofs',
            'pendingPaymentProof',
            'productLimit',
            'productCount',
            'remainingProductSlots',
            'planPrice',
            'paymentNumber',
            'paymentWhatsappUrl'
        ));
    }

    public function store(Request $request)
    {
        $store = Auth::user()->stores()->first();

        if (! $store) {
            return redirect()
                ->route('stores.create')
                ->with('error', 'Primero debes crear una tienda.');
        }

        if ($store->hasUnlimitedProducts()) {
            return redirect()
                ->route('seller.plan.index')
                ->with('success', 'Tu tienda ya tiene productos ilimitados activos.');
        }

        if ($store->paymentProofs()->where('status', PaymentProof::STATUS_PENDING)->exists()) {
            return redirect()
                ->route('seller.plan.index')
                ->with('error', 'Ya tienes un comprobante pendiente de revisión.');
        }

        $validated = $request->validate([
            'method' => ['bail', 'required', Rule::in(['yape', 'bcp'])],
            'reference' => ['nullable', 'string', 'max:255'],
            'proof' => [
                'required',
                File::types(['jpg', 'jpeg', 'png', 'webp', 'pdf'])->max(4096),
            ],
        ]);

        $hash = hash_file('sha256', $request->file('proof')->getRealPath());
        $reference = preg_replace('/\s+/', '', mb_strtoupper(trim($validated['reference'] ?? '')));
        $referenceKey = $reference !== '' ? hash('sha256', $validated['method'].'|'.$reference) : null;
        $proofPath = null;

        try {
            DB::transaction(function () use ($store, $request, $validated, $hash, $referenceKey, &$proofPath) {
                $store = Store::whereKey($store->id)->lockForUpdate()->firstOrFail();
                if ($store->hasUnlimitedProducts() || $store->paymentProofs()->where('status', PaymentProof::STATUS_PENDING)->exists()) {
                    throw ValidationException::withMessages(['proof' => 'Ya tienes el plan activo o un comprobante pendiente.']);
                }
                if (PaymentProof::where('proof_hash', $hash)->exists()
                    || ($referenceKey && PaymentProof::where('reference_key', $referenceKey)->exists())) {
                    throw ValidationException::withMessages(['proof' => 'Este comprobante o numero de operacion ya fue enviado.']);
                }
                if (! $proofPath) {
                    $proofPath = $request->file('proof')->store('payment-proofs', 'local');
                }
                if (! $proofPath) {
                    throw new \RuntimeException('No se pudo guardar el comprobante.');
                }
                $store->paymentProofs()->create([
                    'method' => $validated['method'],
                    'amount' => config('vendefacil.unlimited_products_price'),
                    'payment_number' => config('vendefacil.payment_number'),
                    'reference' => $validated['reference'] ?? null,
                    'proof_path' => $proofPath,
                    'proof_hash' => $hash,
                    'reference_key' => $referenceKey,
                    'status' => PaymentProof::STATUS_PENDING,
                ]);
                foreach (User::where('is_admin', true)->cursor() as $admin) {
                    $admin->notify(new MarketplaceNotice('Nuevo pago pendiente', $store->name.' envio un comprobante.', '/admin/pagos'));
                }
            }, 3);
        } catch (\Throwable $exception) {
            if ($proofPath) {
                Storage::disk('local')->delete($proofPath);
            }
            if ($exception instanceof \Illuminate\Database\UniqueConstraintViolationException) {
                throw ValidationException::withMessages(['proof' => 'Este comprobante o numero de operacion ya fue enviado.']);
            }
            throw $exception;
        }

        return redirect()
            ->route('seller.plan.index')
            ->with('success', 'Comprobante enviado. El administrador lo revisará para activar el plan.');
    }

    private function paymentWhatsappUrl($store): string
    {
        $message = 'Hola, quiero activar productos ilimitados para mi tienda '
            . $store->name
            . '. Ya subí o voy a subir mi comprobante en VendeFácil.';

        return 'https://wa.me/' . config('vendefacil.payment_whatsapp')
            . '?text=' . rawurlencode($message);
    }

    public function show(Request $request, PaymentProof $paymentProof)
    {
        abort_unless($request->user()->is_admin || $paymentProof->store->user_id === $request->user()->id, 403);
        $path = $paymentProof->proof_path;
        abort_unless(str_starts_with($path, 'payment-proofs/') && ! str_contains($path, '..') && ! str_contains($path, '\\'), 404);
        abort_unless(Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->download($path, 'comprobante-'.$paymentProof->id.'.'.pathinfo($path, PATHINFO_EXTENSION), [
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
