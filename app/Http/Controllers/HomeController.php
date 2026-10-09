<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | FILTROS
        |--------------------------------------------------------------------------
        */

        $search = trim($request->get('buscar', ''));

        $categorySlug = trim($request->get('categoria', ''));

        $city = trim($request->get('ciudad', ''));

        $delivery = $request->boolean('delivery');


        /*
        |--------------------------------------------------------------------------
        | CATEGORÍAS
        |--------------------------------------------------------------------------
        */

        $categories = Category::query()
            ->withCount([
                'products' => function ($query) {
                    $query
                        ->where('active', true)
                        ->where('stock', '>', 0)
                        ->whereHas('store', function ($storeQuery) {
                            $storeQuery->where('active', true);
                        });
                }
            ])
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | CIUDADES
        |--------------------------------------------------------------------------
        */

        $cities = Store::query()
            ->where('active', true)
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->select('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');


        /*
        |--------------------------------------------------------------------------
        | CATEGORÍA SELECCIONADA
        |--------------------------------------------------------------------------
        */

        $selectedCategory = null;

        if ($categorySlug !== '') {
            $selectedCategory = Category::where('slug', $categorySlug)->first();
        }


        /*
        |--------------------------------------------------------------------------
        | PRODUCTOS
        |--------------------------------------------------------------------------
        |
        | Busca por:
        | - nombre del producto
        | - descripción
        | - nombre de tienda
        | - categoría
        |
        */

        $productsQuery = Product::query()
            ->where('active', true)
            ->where('stock', '>', 0)
            ->whereHas('store', function ($query) {
                $query->where('active', true);
            })
            ->with([
                'store',
                'category',
            ]);


        /*
        |--------------------------------------------------------------------------
        | FILTRO DE BÚSQUEDA
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {

            $productsQuery->where(function ($query) use ($search) {

                $query
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhereHas('store', function ($storeQuery) use ($search) {

                        $storeQuery
                            ->where('name', 'like', '%' . $search . '%')
                            ->orWhere('description', 'like', '%' . $search . '%')
                            ->orWhere('city', 'like', '%' . $search . '%')
                            ->orWhere('address', 'like', '%' . $search . '%');

                    })
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {

                        $categoryQuery
                            ->where('name', 'like', '%' . $search . '%');

                    });

            });

        }


        /*
        |--------------------------------------------------------------------------
        | FILTRO POR CATEGORÍA
        |--------------------------------------------------------------------------
        */

        if ($selectedCategory) {

            $productsQuery->where(
                'category_id',
                $selectedCategory->id
            );

        }


        /*
        |--------------------------------------------------------------------------
        | FILTROS POR CIUDAD Y DELIVERY
        |--------------------------------------------------------------------------
        */

        if ($city !== '' || $delivery) {

            $productsQuery->whereHas('store', function ($storeQuery) use ($city, $delivery) {

                if ($city !== '') {
                    $storeQuery->where('city', $city);
                }

                if ($delivery) {
                    $storeQuery->where('delivery_available', true);
                }

            });

        }


        /*
        |--------------------------------------------------------------------------
        | PRODUCTOS PARA EL HOME
        |--------------------------------------------------------------------------
        */

        $products = $productsQuery
            ->latest()
            ->paginate(12, ['*'], 'productos')
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | TIENDAS
        |--------------------------------------------------------------------------
        */

        $stores = Store::query()
            ->where('active', true)
            ->withCount([
                'products' => function ($query) {
                    $query->where('active', true);
                }
            ])
            ->when($search !== '', function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%')
                        ->orWhere('city', 'like', '%' . $search . '%')
                        ->orWhere('address', 'like', '%' . $search . '%');

                });

            })
            ->when($city !== '', function ($query) use ($city) {
                $query->where('city', $city);
            })
            ->when($delivery, function ($query) {
                $query->where('delivery_available', true);
            })
            ->orderByRaw(
                'case when featured = 1 and (featured_until is null or featured_until > ?) then 1 else 0 end desc',
                [now()]
            )
            ->latest()
            ->take(9)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | PRODUCTOS DESTACADOS
        |--------------------------------------------------------------------------
        |
        | Los usamos principalmente cuando no hay filtros activos.
        |
        */

        $featuredProducts = Product::query()
            ->where('active', true)
            ->where('stock', '>', 0)
            ->whereHas('store', function ($query) {
                $query->where('active', true);
            })
            ->with([
                'store',
                'category',
            ])
            ->latest()
            ->take(8)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | ESTADÍSTICAS
        |--------------------------------------------------------------------------
        */

        $storesCount = Store::query()
            ->where('active', true)
            ->count();


        $productsCount = Product::query()
            ->where('active', true)
            ->whereHas('store', function ($query) {
                $query->where('active', true);
            })
            ->count();


        /*
        |--------------------------------------------------------------------------
        | DEVOLVER HOME
        |--------------------------------------------------------------------------
        */

        return view('home', compact(
            'categories',
            'selectedCategory',
            'categorySlug',
            'cities',
            'city',
            'delivery',
            'products',
            'featuredProducts',
            'stores',
            'storesCount',
            'productsCount',
            'search'
        ));
    }
}
