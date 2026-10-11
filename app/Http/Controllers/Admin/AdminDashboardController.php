<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentProof;
use App\Models\Product;
use App\Models\Store;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $approvedPayments = PaymentProof::query()
            ->where('status', PaymentProof::STATUS_APPROVED);

        $totalRevenue = (clone $approvedPayments)->sum('amount');
        $yapeRevenue = (clone $approvedPayments)->where('method', 'yape')->sum('amount');
        $bcpRevenue = (clone $approvedPayments)->where('method', 'bcp')->sum('amount');

        $pendingPaymentProofs = PaymentProof::where('status', PaymentProof::STATUS_PENDING)->count();
        $approvedPaymentProofs = PaymentProof::where('status', PaymentProof::STATUS_APPROVED)->count();
        $rejectedPaymentProofs = PaymentProof::where('status', PaymentProof::STATUS_REJECTED)->count();

        $totalStores = Store::count();
        $activeStores = Store::where('active', true)->count();
        $unlimitedStores = Store::where('unlimited_products', true)->count();
        $featuredStores = Store::query()
            ->where('active', true)
            ->where('featured', true)
            ->where(function ($query) {
                $query
                    ->whereNull('featured_until')
                    ->orWhere('featured_until', '>', now());
            })
            ->count();
        $verifiedStores = Store::where('verified', true)->count();
        $totalProducts = Product::count();

        $pendingPayments = PaymentProof::query()
            ->with(['store.user'])
            ->where('status', PaymentProof::STATUS_PENDING)
            ->latest()
            ->take(6)
            ->get();

        $recentDecisions = PaymentProof::query()
            ->with(['store', 'reviewer'])
            ->whereNotNull('reviewed_at')
            ->latest('reviewed_at')
            ->take(10)
            ->get();

        $topUnlimitedStores = Store::query()
            ->withCount('products')
            ->where('unlimited_products', true)
            ->latest('unlimited_products_activated_at')
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'yapeRevenue',
            'bcpRevenue',
            'pendingPaymentProofs',
            'approvedPaymentProofs',
            'rejectedPaymentProofs',
            'totalStores',
            'activeStores',
            'unlimitedStores',
            'featuredStores',
            'verifiedStores',
            'totalProducts',
            'pendingPayments',
            'recentDecisions',
            'topUnlimitedStores'
        ));
    }
}
