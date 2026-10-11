<?php

namespace App\Http\Controllers;

use App\Models\PaymentProof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $proofPath = $request->file('proof')->store('payment-proofs', 'public');

        $store->paymentProofs()->create([
            'method' => $validated['method'],
            'amount' => config('vendefacil.unlimited_products_price'),
            'payment_number' => config('vendefacil.payment_number'),
            'reference' => $validated['reference'] ?? null,
            'proof_path' => $proofPath,
            'status' => PaymentProof::STATUS_PENDING,
        ]);

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
}
