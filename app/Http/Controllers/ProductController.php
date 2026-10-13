<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Support\SafePublicStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Store;
use App\Support\OptimizedImage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class ProductController extends Controller
{
    use SafePublicStorage;

    public function index()
    {
        $store = Auth::user()->stores()->first();

        if (!$store) {
            return redirect()
                ->route('stores.create')
                ->with('error', 'Primero debes crear una tienda.');
        }

        $products = $store->products()
            ->with('category')
            ->latest()
            ->get();

        $productLimit = config('vendefacil.free_product_limit');
        $productCount = $products->count();
        $remainingProductSlots = $store->remainingFreeProducts($productCount);
        $planPrice = config('vendefacil.unlimited_products_price');
        $paymentNumber = config('vendefacil.payment_number');
        $paymentWhatsappUrl = $this->paymentWhatsappUrl($store);

        return view('products.index', compact(
            'store',
            'products',
            'productLimit',
            'productCount',
            'remainingProductSlots',
            'planPrice',
            'paymentNumber',
            'paymentWhatsappUrl'
        ));
    }


    public function create()
    {
        $store = Auth::user()->stores()->first();

        if (!$store) {
            return redirect()
                ->route('stores.create')
                ->with('error', 'Primero debes crear una tienda.');
        }

        $productLimit = config('vendefacil.free_product_limit');
        $productCount = $store->products()->count();
        $remainingProductSlots = $store->remainingFreeProducts($productCount);
        $hasReachedFreeLimit = ! $store->canCreateMoreProducts($productCount);
        $planPrice = config('vendefacil.unlimited_products_price');
        $paymentNumber = config('vendefacil.payment_number');
        $paymentWhatsappUrl = $this->paymentWhatsappUrl($store);

        $categories = Category::orderBy('name')->get();

        return view('products.create', compact(
            'store',
            'categories',
            'productLimit',
            'productCount',
            'remainingProductSlots',
            'hasReachedFreeLimit',
            'planPrice',
            'paymentNumber',
            'paymentWhatsappUrl'
        ));
    }


    public function store(Request $request)
    {
        $store = Auth::user()->stores()->first();

        if (!$store) {
            return redirect()
                ->route('stores.create')
                ->with('error', 'Primero debes crear una tienda.');
        }

        if (! $store->canCreateMoreProducts()) {
            return redirect()
                ->route('products.index')
                ->with('error', 'Ya usaste tus 10 productos gratis. Para publicar más, activa el plan de productos ilimitados con Yape o BCP.');
        }

        $validated = $request->validate([
            'category_id' => ['bail', 'required', 'integer', 'exists:categories,id'],
            'name' => ['bail', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'price' => ['bail', 'required', 'numeric', 'min:0', 'max:99999999.99'],
            'stock' => ['bail', 'required', 'integer', 'min:0', 'max:999999'],
            'image' => [
                'nullable',
                File::image()->types(['jpg', 'jpeg', 'png', 'webp'])->max(4096),
                Rule::dimensions()->maxWidth(4000)->maxHeight(4000),
            ],
            'gallery_images' => ['nullable', 'array', 'max:6'],
            'gallery_images.*' => [
                File::image()->types(['jpg', 'jpeg', 'png', 'webp'])->max(4096),
                Rule::dimensions()->maxWidth(4000)->maxHeight(4000),
            ],
        ]);

        $uploadedPaths = [];
        try {
            DB::transaction(function () use ($store, $request, $validated, &$uploadedPaths) {
                $store = Store::whereKey($store->id)->lockForUpdate()->firstOrFail();
                if (! $store->canCreateMoreProducts()) {
                    throw ValidationException::withMessages(['name' => 'Alcanzaste el limite del plan gratuito.']);
                }
                $imagePath = null;

                if ($request->hasFile('image')) {
                    $imagePath = OptimizedImage::store($request->file('image'), 'products');
                    $uploadedPaths[] = $imagePath;
                }

                $galleryImages = $this->storeUploadedGalleryImages($request, 'gallery_images', 'products/gallery');
                $uploadedPaths = array_merge($uploadedPaths, $galleryImages);

                $baseSlug = Str::slug($validated['name']) ?: 'producto';

                $slug = $baseSlug;

                $counter = 1;

                while (
                    Product::where('store_id', $store->id)
                        ->where('slug', $slug)
                        ->exists()
                ) {
                    $slug = $baseSlug . '-' . $counter;

                    $counter++;
                }

                $store->products()->create([
                    'category_id' => $validated['category_id'],
                    'name' => $validated['name'],
                    'slug' => $slug,
                    'description' => $validated['description'] ?? null,
                    'price' => $validated['price'],
                    'stock' => $validated['stock'],
                    'image' => $imagePath,
                    'gallery_images' => $galleryImages,
                ]);
            });
        } catch (\Throwable $exception) {
            foreach ($uploadedPaths as $path) { $this->deletePublicUpload($path, 'products/'); }
            throw $exception;
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Producto creado correctamente.');
    }


    public function edit(Product $product)
    {
        $store = Auth::user()->stores()->first();

        if (!$store || $product->store_id !== $store->id) {
            abort(403);
        }

        $categories = Category::orderBy('name')->get();

        return view('products.edit', compact(
            'store',
            'product',
            'categories'
        ));
    }


    public function update(Request $request, Product $product)
    {
        $store = Auth::user()->stores()->first();

        if (!$store || $product->store_id !== $store->id) {
            abort(403);
        }

        $validated = $request->validate([
            'category_id' => ['bail', 'required', 'integer', 'exists:categories,id'],
            'name' => ['bail', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'price' => ['bail', 'required', 'numeric', 'min:0', 'max:99999999.99'],
            'stock' => ['bail', 'required', 'integer', 'min:0', 'max:999999'],
            'image' => [
                'nullable',
                File::image()->types(['jpg', 'jpeg', 'png', 'webp'])->max(4096),
                Rule::dimensions()->maxWidth(4000)->maxHeight(4000),
            ],
            'gallery_images' => ['nullable', 'array', 'max:6'],
            'gallery_images.*' => [
                File::image()->types(['jpg', 'jpeg', 'png', 'webp'])->max(4096),
                Rule::dimensions()->maxWidth(4000)->maxHeight(4000),
            ],
            'remove_gallery_images' => ['nullable', 'array'],
            'remove_gallery_images.*' => ['string', 'max:255'],
            'active' => ['nullable', 'boolean'],
        ]);

        $uploadedPaths = [];
        try {
            $imagePath = $product->image;
            $oldImagePath = $product->image;
            $oldGalleryImages = $product->gallery_images ?? [];

            if ($request->hasFile('image')) {

                $imagePath = OptimizedImage::store($request->file('image'), 'products');
                $uploadedPaths[] = $imagePath;
            }

            $newGalleryImages = $this->storeUploadedGalleryImages($request, 'gallery_images', 'products/gallery');
            $uploadedPaths = array_merge($uploadedPaths, $newGalleryImages);

            $galleryImages = $this->syncGalleryImages(
                $product->gallery_images ?? [],
                $request->input('remove_gallery_images', []),
                $newGalleryImages,
                'products/gallery/',
                6
            );

            $baseSlug = Str::slug($validated['name']) ?: 'producto';

            $slug = $baseSlug;

            $counter = 1;

            while (
                Product::where('store_id', $store->id)
                    ->where('slug', $slug)
                    ->where('id', '!=', $product->id)
                    ->exists()
            ) {
                $slug = $baseSlug . '-' . $counter;

                $counter++;
            }

            $product->fill([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'slug' => $slug,
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'image' => $imagePath,
                'gallery_images' => $galleryImages,
            ])->forceFill([
                'active' => $request->boolean('active'),
            ])->save();
        } catch (\Throwable $exception) {
            foreach ($uploadedPaths as $path) {
                $this->deletePublicUpload($path, 'products/');
            }
            throw $exception;
        }

        if ($oldImagePath !== $imagePath) { $this->deletePublicUpload($oldImagePath, 'products/'); }
        foreach (array_diff($oldGalleryImages, $galleryImages) as $image) { $this->deletePublicUpload($image, 'products/gallery/'); }

        return redirect()
            ->route('products.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function toggle(Product $product)
    {
        $store = Auth::user()->stores()->first();

        if (!$store || $product->store_id !== $store->id) {
            abort(403);
        }

        $product->forceFill([
            'active' => !$product->active,
        ])->save();

        return redirect()
            ->route('products.index')
            ->with(
                'success',
                $product->active
                    ? 'Producto activado correctamente.'
                    : 'Producto ocultado correctamente.'
            );
    }


    public function destroy(Product $product)
    {
        $store = Auth::user()->stores()->first();

        if (!$store || $product->store_id !== $store->id) {
            abort(403);
        }

        $imagePath = $product->image;
        $galleryImages = $product->gallery_images ?? [];
        $product->delete();

        $this->deletePublicUpload($imagePath, 'products/');
        foreach ($galleryImages as $galleryImage) {
            $this->deletePublicUpload($galleryImage, 'products/gallery/');
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Producto eliminado correctamente.');
    }

    private function paymentWhatsappUrl($store): string
    {
        $message = 'Hola, quiero activar productos ilimitados para mi tienda '
            . $store->name
            . '. Ya realicé o quiero realizar el pago.';

        return 'https://wa.me/' . config('vendefacil.payment_whatsapp')
            . '?text=' . rawurlencode($message);
    }

}
