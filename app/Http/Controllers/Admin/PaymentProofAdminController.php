<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentProof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentProofAdminController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('estado', PaymentProof::STATUS_PENDING);

        if (! in_array($status, ['todos', PaymentProof::STATUS_PENDING, PaymentProof::STATUS_APPROVED, PaymentProof::STATUS_REJECTED], true)) {
            $status = PaymentProof::STATUS_PENDING;
        }

        $paymentProofs = PaymentProof::query()
            ->with(['store.user', 'reviewer'])
            ->when($status !== 'todos', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $pendingPaymentProofs = PaymentProof::where('status', PaymentProof::STATUS_PENDING)->count();
        $approvedPaymentProofs = PaymentProof::where('status', PaymentProof::STATUS_APPROVED)->count();
        $rejectedPaymentProofs = PaymentProof::where('status', PaymentProof::STATUS_REJECTED)->count();

        return view('admin.payments.index', compact(
            'paymentProofs',
            'status',
            'pendingPaymentProofs',
            'approvedPaymentProofs',
            'rejectedPaymentProofs'
        ));
    }

    public function approve(PaymentProof $paymentProof)
    {
        if (! $paymentProof->isPending()) {
            return redirect()
                ->back()
                ->with('error', 'Este comprobante ya fue revisado.');
        }

        $paymentProof->load('store');

        $paymentProof->forceFill([
            'status' => PaymentProof::STATUS_APPROVED,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_notes' => null,
        ])->save();

        $paymentProof->store->forceFill([
            'unlimited_products' => true,
            'unlimited_products_activated_at' => now(),
        ])->save();

        return redirect()
            ->back()
            ->with('success', 'Pago aprobado. La tienda ya tiene productos ilimitados.');
    }

    public function reject(Request $request, PaymentProof $paymentProof)
    {
        if (! $paymentProof->isPending()) {
            return redirect()
                ->back()
                ->with('error', 'Este comprobante ya fue revisado.');
        }

        $validated = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $paymentProof->forceFill([
            'status' => PaymentProof::STATUS_REJECTED,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_notes' => $validated['admin_notes'] ?? null,
        ])->save();

        return redirect()
            ->back()
            ->with('success', 'Comprobante rechazado. La tienda sigue en el plan gratuito.');
    }
}
