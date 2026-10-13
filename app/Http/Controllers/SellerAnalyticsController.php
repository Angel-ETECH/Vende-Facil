<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SellerAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $store = $request->user()->stores()->first();
        if (! $store) { return redirect()->route('stores.create'); }
        $days = in_array($request->integer('dias'), [7, 30, 90], true) ? $request->integer('dias') : 30;
        $since = now()->subDays($days - 1)->startOfDay();
        $visits = $store->visits()->where('created_at', '>=', $since)->count();
        $clicks = $store->whatsappClicks()->where('created_at', '>=', $since)->count();
        $topProducts = $store->products()->withCount(['whatsappClicks as inquiries_count' => fn ($q) => $q->where('created_at', '>=', $since)])
            ->orderByDesc('inquiries_count')->orderBy('name')->limit(10)->get();
        $dailyVisits = $store->visits()->where('created_at', '>=', $since)->selectRaw('DATE(created_at) as day, COUNT(*) as total')->groupBy('day')->pluck('total', 'day');
        $dailyClicks = $store->whatsappClicks()->where('created_at', '>=', $since)->selectRaw('DATE(created_at) as day, COUNT(*) as total')->groupBy('day')->pluck('total', 'day');
        return view('analytics.index', compact('store', 'days', 'visits', 'clicks', 'topProducts', 'dailyVisits', 'dailyClicks'));
    }
}
