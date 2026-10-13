<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentProof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Store;
use App\Notifications\MarketplaceNotice;
use Illuminate\Validation\ValidationException;

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
        DB::transaction(function () use ($paymentProof) {
            $store = Store::whereKey($paymentProof->store_id)->lockForUpdate()->firstOrFail();
            $paymentProof = PaymentProof::whereKey($paymentProof->id)->lockForUpdate()->firstOrFail();
            if (! $paymentProof->isPending()) {
                throw ValidationException::withMessages(['payment' => 'Este comprobante ya fue revisado.']);
            }
            $paymentProof->forceFill([
                'status' => PaymentProof::STATUS_APPROVED,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'admin_notes' => null,
            ])->save();

            $store->forceFill([
                'unlimited_products' => true,
                'unlimited_products_activated_at' => now(),
            ])->save();
            $store->user->notify(new MarketplaceNotice('Pago aprobado', 'Tu tienda ya tiene productos ilimitados.', '/mi-plan'));
        }, 3);

        return redirect()
            ->back()
            ->with('success', 'Pago aprobado. La tienda ya tiene productos ilimitados.');
    }

    public function reject(Request $request, PaymentProof $paymentProof)
    {
        $validated = $request->validate([
            'admin_notes' => ['required', 'string', 'max:1000'],
        ]);
        DB::transaction(function () use ($paymentProof, $validated) {
            $store = Store::whereKey($paymentProof->store_id)->lockForUpdate()->firstOrFail();
            $paymentProof = PaymentProof::whereKey($paymentProof->id)->lockForUpdate()->firstOrFail();
            if (! $paymentProof->isPending()) {
                throw ValidationException::withMessages(['payment' => 'Este comprobante ya fue revisado.']);
            }
            $paymentProof->forceFill([
                'status' => PaymentProof::STATUS_REJECTED,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'admin_notes' => $validated['admin_notes'],
            ])->save();
            $store->user->notify(new MarketplaceNotice('Comprobante rechazado', $validated['admin_notes'], '/mi-plan'));
        }, 3);

        return redirect()
            ->back()
            ->with('success', 'Comprobante rechazado. La tienda sigue en el plan gratuito.');
    }
}
