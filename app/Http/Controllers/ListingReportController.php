<?php

namespace App\Http\Controllers;

use App\Models\ListingReport;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use App\Notifications\MarketplaceNotice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ListingReportController extends Controller
{
    public function create(Request $request, Store $store)
    {
        abort_unless($store->active, 404);
        $request->validate(['producto' => ['nullable', 'integer', 'min:1']]);
        $product = $request->filled('producto') ? $store->products()->where('active', true)->findOrFail($request->integer('producto')) : null;
        return view('reports.create', compact('store', 'product'));
    }

    public function store(Request $request, Store $store)
    {
        abort_unless($store->active, 404);
        $data = $request->validate([
            'reason' => ['required', Rule::in(['fraud', 'prohibited', 'misleading', 'other'])],
            'details' => ['required', 'string', 'min:10', 'max:2000'],
            'product_id' => ['nullable', 'integer', Rule::exists('products', 'id')->where('store_id', $store->id)->where('active', true)],
        ]);
        DB::transaction(function () use ($request, $store, $data) {
            User::whereKey($request->user()->id)->lockForUpdate()->firstOrFail();
            if (ListingReport::where('user_id', $request->user()->id)->where('store_id', $store->id)
                ->where('product_id', $data['product_id'] ?? null)->where('status', 'pending')->exists()) {
                throw ValidationException::withMessages(['details' => 'Ya tienes una denuncia pendiente para esta publicacion.']);
            }
            $report = new ListingReport($data);
            $report->store_id = $store->id;
            $report->user_id = $request->user()->id;
            $report->save();
            foreach (User::where('is_admin', true)->cursor() as $admin) {
                $admin->notify(new MarketplaceNotice('Nueva denuncia', 'Hay una publicacion por revisar en '.$store->name.'.', '/admin/denuncias'));
            }
        });
        return redirect()->route('stores.show', $store)->with('success', 'Denuncia enviada. Revisaremos la publicacion.');
    }

    public function index(Request $request)
    {
        $status = in_array($request->query('estado'), ['resolved', 'dismissed'], true) ? $request->query('estado') : 'pending';
        $reports = ListingReport::with(['user', 'store', 'product', 'reviewer'])->where('status', $status)->latest()->paginate(15)->withQueryString();
        return view('admin.reports.index', compact('reports', 'status'));
    }

    public function review(Request $request, ListingReport $listingReport)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['resolved', 'dismissed'])],
            'admin_notes' => ['required', 'string', 'max:1000'],
            'hide_listing' => ['nullable', 'boolean'],
        ]);
        DB::transaction(function () use ($listingReport, $request, $data) {
            $report = ListingReport::whereKey($listingReport->id)->lockForUpdate()->firstOrFail();
            if ($report->status !== 'pending') {
                throw ValidationException::withMessages(['report' => 'Esta denuncia ya fue revisada.']);
            }
            if ($data['status'] === 'resolved' && $request->boolean('hide_listing')) {
                ($report->product ?? $report->store)->forceFill(['active' => false])->save();
            }
            $report->forceFill(['status' => $data['status'], 'admin_notes' => $data['admin_notes'],
                'reviewed_by' => $request->user()->id, 'reviewed_at' => now()])->save();
            $report->user->notify(new MarketplaceNotice('Denuncia revisada', $data['admin_notes'], '/notificaciones'));
        });
        return back()->with('success', 'Decision guardada.');
    }
}
