<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentProof;
use App\Models\Product;
use App\Models\Store;
use App\Services\StoreUploadCleanup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreAdminController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'buscar' => ['nullable', 'string', 'max:150'],
            'ciudad' => ['nullable', 'string', 'max:100'],
            'estado' => ['nullable', Rule::in(['todos', 'activa', 'inactiva'])],
            'delivery' => ['nullable', Rule::in(['todos', 'si', 'no'])],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);
        $search = trim($filters['buscar'] ?? '');
        $city = trim($filters['ciudad'] ?? '');
        $status = $filters['estado'] ?? 'todos';
        $delivery = $filters['delivery'] ?? 'todos';

        if (! in_array($status, ['todos', 'activa', 'inactiva'], true)) {
            $status = 'todos';
        }

        if (! in_array($delivery, ['todos', 'si', 'no'], true)) {
            $delivery = 'todos';
        }

        $stores = Store::query()
            ->with('user')
            ->withCount([
                'products',
                'visits',
                'whatsappClicks',
                'visits as visits_today_count' => function ($query) {
                    $query->whereDate('created_at', today());
                },
                'whatsappClicks as whatsapp_clicks_today_count' => function ($query) {
                    $query->whereDate('created_at', today());
                },
            ])
            ->when($search !== '', function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('whatsapp', 'like', '%' . $search . '%')
                        ->orWhere('city', 'like', '%' . $search . '%')
                        ->orWhere('address', 'like', '%' . $search . '%')
                        ->orWhereHas('user', function ($userQuery) use ($search) {

                            $userQuery
                                ->where('name', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%');

                        });

                });

            })
            ->when($city !== '', function ($query) use ($city) {
                $query->where('city', $city);
            })
            ->when($status !== 'todos', function ($query) use ($status) {
                $query->where('active', $status === 'activa');
            })
            ->when($delivery !== 'todos', function ($query) use ($delivery) {
                $query->where('delivery_available', $delivery === 'si');
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();


        $totalStores = Store::count();

        $activeStores = Store::where('active', true)->count();

        $totalProducts = Product::count();

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

        $pendingPaymentProofs = PaymentProof::where('status', PaymentProof::STATUS_PENDING)->count();

        $cities = Store::query()
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');


        return view('admin.stores.index', compact(
            'stores',
            'search',
            'city',
            'status',
            'delivery',
            'cities',
            'totalStores',
            'activeStores',
            'totalProducts',
            'unlimitedStores',
            'featuredStores',
            'verifiedStores',
            'pendingPaymentProofs'
        ));
    }

    public function toggle(Store $store)
    {
        $store->forceFill([
            'active' => !$store->active,
        ])->save();

        return redirect()
            ->route('admin.stores.index')
            ->with(
                'success',
                $store->active
                    ? 'La tienda "' . $store->name . '" fue activada correctamente.'
                    : 'La tienda "' . $store->name . '" fue pausada correctamente.'
            );
    }

    public function toggleUnlimitedProducts(Store $store)
    {
        $willActivate = ! $store->unlimited_products;

        $store->forceFill([
            'unlimited_products' => $willActivate,
            'unlimited_products_activated_at' => $willActivate ? now() : null,
        ])->save();

        return redirect()
            ->back()
            ->with(
                'success',
                $willActivate
                    ? 'La tienda "' . $store->name . '" ahora tiene productos ilimitados.'
                    : 'La tienda "' . $store->name . '" volvió al límite gratuito de productos.'
            );
    }

    public function toggleFeatured(Store $store)
    {
        $willActivate = ! $store->featured;

        $store->forceFill([
            'featured' => $willActivate,
            'featured_until' => $willActivate
                ? now()->addDays(config('vendefacil.featured_days'))
                : null,
        ])->save();

        return redirect()
            ->back()
            ->with(
                'success',
                $willActivate
                    ? 'La tienda "' . $store->name . '" ahora está destacada en la página principal.'
                    : 'La tienda "' . $store->name . '" dejó de estar destacada.'
            );
    }

    public function toggleVerified(Store $store)
    {
        $willVerify = ! $store->verified;

        $store->forceFill([
            'verified' => $willVerify,
            'verified_at' => $willVerify ? now() : null,
        ])->save();

        return redirect()
            ->back()
            ->with(
                'success',
                $willVerify
                    ? 'La tienda "' . $store->name . '" ahora aparece como verificada.'
                    : 'La tienda "' . $store->name . '" dejó de aparecer como verificada.'
            );
    }

    public function show(Store $store)
    {
        $store->load([
            'user',
            'paymentProofs' => function ($query) {
                $query
                    ->with('reviewer')
                    ->latest();
            },
            'products' => function ($query) {
                $query
                    ->with('category')
                    ->withCount('whatsappClicks')
                    ->latest();
            },
        ]);

        $store->loadCount([
            'products',
            'visits',
            'whatsappClicks',
            'products as active_products_count' => function ($query) {
                $query->where('active', true);
            },
            'visits as visits_today_count' => function ($query) {
                $query->whereDate('created_at', today());
            },
            'whatsappClicks as whatsapp_clicks_today_count' => function ($query) {
                $query->whereDate('created_at', today());
            },
        ]);

        $topProducts = $store->products
            ->sortByDesc('whatsapp_clicks_count')
            ->take(3);

        return view('admin.stores.show', compact('store', 'topProducts'));
    }


    public function destroy(Store $store, StoreUploadCleanup $uploads)
    {
        $manifest = $uploads->collect($store);
        $storeName = $store->name;
        $store->delete();
        $uploads->remove($manifest);

        return redirect()
            ->route('admin.stores.index')
            ->with('success', 'La tienda "' . $storeName . '" fue eliminada correctamente.');
    }
}
