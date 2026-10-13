<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $favorites = $request->user()->favorites()->with(['store', 'product.store'])
            ->where(function ($query) {
                $query->whereHas('store', fn ($q) => $q->where('active', true))
                    ->orWhereHas('product', fn ($q) => $q->where('active', true)->whereHas('store', fn ($s) => $s->where('active', true)));
            })->latest()->paginate(12);
        return view('favorites.index', compact('favorites'));
    }

    public function store(Request $request, string $type, int $id)
    {
        abort_unless(in_array($type, ['store', 'product'], true), 404);
        $target = ($type === 'store' ? Store::query() : Product::query())->findOrFail($id);
        abort_unless($target->active && ($type === 'store' || $target->store->active), 404);
        DB::transaction(function () use ($request, $type, $id) {
            \App\Models\User::whereKey($request->user()->id)->lockForUpdate()->firstOrFail();
            $request->user()->favorites()->firstOrCreate([$type.'_id' => $id]);
        });
        return back()->with('success', 'Guardado en tus favoritos.');
    }

    public function destroy(Request $request, string $type, int $id)
    {
        abort_unless(in_array($type, ['store', 'product'], true), 404);
        $request->user()->favorites()->where($type.'_id', $id)->delete();
        return back()->with('success', 'Favorito eliminado.');
    }
}
