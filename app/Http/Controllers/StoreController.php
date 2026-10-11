<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\StoreVisit;
use App\Support\SafePublicStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class StoreController extends Controller
{
    use SafePublicStorage;

    public function create()
    {
        return view('stores.create');
    }


    public function store(Request $request)
    {
        $request->merge([
            'whatsapp' => $this->normalizePeruWhatsapp($request->whatsapp),
        ]);

        $validated = $request->validate([
            'name' => ['bail', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'whatsapp' => ['bail', 'required', 'regex:/^51[0-9]{9}$/'],
            'address' => [
                Rule::requiredIf(! $request->boolean('delivery_available')),
                'nullable',
                'string',
                'max:255',
            ],
            'city' => ['bail', 'required', 'string', 'max:100'],
            'delivery_available' => ['nullable', 'boolean'],
            'location_url' => ['nullable', 'url', 'max:500'],
            'opening_hours' => ['nullable', 'string', 'max:255'],
            'facebook_url' => ['nullable', 'url', 'max:500'],
            'instagram_url' => ['nullable', 'url', 'max:500'],
            'tiktok_url' => ['nullable', 'url', 'max:500'],
            'website_url' => ['nullable', 'url', 'max:500'],
            'logo' => [
                'nullable',
                File::image()->types(['jpg', 'jpeg', 'png', 'webp'])->max(2048),
                Rule::dimensions()->maxWidth(3000)->maxHeight(3000),
            ],
            'gallery_images' => ['nullable', 'array', 'max:6'],
            'gallery_images.*' => [
                File::image()->types(['jpg', 'jpeg', 'png', 'webp'])->max(4096),
                Rule::dimensions()->maxWidth(4000)->maxHeight(4000),
            ],
        ], [
            'address.required' => 'Agrega una dirección o referencia si tu tienda no ofrece delivery.',
        ]);


        $logoPath = null;


        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')
                ->store('stores/logos', 'public');
        }

        $galleryImages = $this->storeUploadedGalleryImages($request, 'gallery_images', 'stores/gallery');

        $baseSlug = Str::slug($validated['name']) ?: 'tienda';

        Auth::user()->stores()->create([
            'name' => $validated['name'],
            'slug' => $baseSlug . '-' . Str::random(5),
            'logo' => $logoPath,
            'gallery_images' => $galleryImages,
            'description' => $validated['description'] ?? null,
            'whatsapp' => $validated['whatsapp'],
            'address' => $validated['address'] ?? null,
            'location_url' => $validated['location_url'] ?? null,
            'city' => $validated['city'],
            'delivery_available' => $request->boolean('delivery_available'),
            'opening_hours' => $validated['opening_hours'] ?? null,
            'facebook_url' => $validated['facebook_url'] ?? null,
            'instagram_url' => $validated['instagram_url'] ?? null,
            'tiktok_url' => $validated['tiktok_url'] ?? null,
            'website_url' => $validated['website_url'] ?? null,
        ]);


        return redirect()
            ->route('dashboard')
            ->with('success', 'Tu tienda fue creada correctamente.');
    }


    public function edit()
    {
        $store = Auth::user()->stores()->first();


        if (!$store) {
            return redirect()
                ->route('stores.create')
                ->with('error', 'Primero debes crear una tienda.');
        }


        return view('stores.edit', compact('store'));
    }


    public function update(Request $request)
    {
        $store = Auth::user()->stores()->first();


        if (!$store) {
            return redirect()
                ->route('stores.create')
                ->with('error', 'Primero debes crear una tienda.');
        }

        $request->merge([
            'whatsapp' => $this->normalizePeruWhatsapp($request->whatsapp),
        ]);

        $validated = $request->validate([
            'name' => ['bail', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'whatsapp' => ['bail', 'required', 'regex:/^51[0-9]{9}$/'],
            'address' => [
                Rule::requiredIf(! $request->boolean('delivery_available')),
                'nullable',
                'string',
                'max:255',
            ],
            'city' => ['bail', 'required', 'string', 'max:100'],
            'delivery_available' => ['nullable', 'boolean'],
            'location_url' => ['nullable', 'url', 'max:500'],
            'opening_hours' => ['nullable', 'string', 'max:255'],
            'facebook_url' => ['nullable', 'url', 'max:500'],
            'instagram_url' => ['nullable', 'url', 'max:500'],
            'tiktok_url' => ['nullable', 'url', 'max:500'],
            'website_url' => ['nullable', 'url', 'max:500'],
            'logo' => [
                'nullable',
                File::image()->types(['jpg', 'jpeg', 'png', 'webp'])->max(2048),
                Rule::dimensions()->maxWidth(3000)->maxHeight(3000),
            ],
            'gallery_images' => ['nullable', 'array', 'max:6'],
            'gallery_images.*' => [
                File::image()->types(['jpg', 'jpeg', 'png', 'webp'])->max(4096),
                Rule::dimensions()->maxWidth(4000)->maxHeight(4000),
            ],
            'remove_gallery_images' => ['nullable', 'array'],
            'remove_gallery_images.*' => ['string', 'max:255'],
        ], [
            'address.required' => 'Agrega una dirección o referencia si tu tienda no ofrece delivery.',
        ]);


        $logoPath = $store->logo;


        if ($request->hasFile('logo')) {

            $this->deletePublicUpload($store->logo, 'stores/logos/');


            $logoPath = $request->file('logo')
                ->store('stores/logos', 'public');
        }

        $galleryImages = $this->syncGalleryImages(
            $store->gallery_images ?? [],
            $request->input('remove_gallery_images', []),
            $this->storeUploadedGalleryImages($request, 'gallery_images', 'stores/gallery'),
            'stores/gallery/',
            6
        );

        $store->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'whatsapp' => $validated['whatsapp'],
            'address' => $validated['address'] ?? null,
            'location_url' => $validated['location_url'] ?? null,
            'city' => $validated['city'],
            'delivery_available' => $request->boolean('delivery_available'),
            'opening_hours' => $validated['opening_hours'] ?? null,
            'facebook_url' => $validated['facebook_url'] ?? null,
            'instagram_url' => $validated['instagram_url'] ?? null,
            'tiktok_url' => $validated['tiktok_url'] ?? null,
            'website_url' => $validated['website_url'] ?? null,
            'gallery_images' => $galleryImages,
            'logo' => $logoPath,
        ]);


        return redirect()
            ->route('dashboard')
            ->with('success', 'Tu tienda fue actualizada correctamente.');
    }

    public function toggle()
    {
        $store = Auth::user()->stores()->first();

        if (!$store) {
            return redirect()
                ->route('stores.create')
                ->with('error', 'Primero debes crear una tienda.');
        }

        $store->forceFill([
            'active' => !$store->active,
        ])->save();

        return redirect()
            ->route('dashboard')
            ->with(
                'success',
                $store->active
                    ? 'Tu tienda fue activada correctamente.'
                    : 'Tu tienda fue pausada correctamente.'
            );
    }


    public function show(Store $store, Request $request)
    {
        if (!$store->active) {
            abort(404);
        }

        if (Auth::id() !== $store->user_id) {
            StoreVisit::create([
                'store_id' => $store->id,
                'ip_address' => $request->ip(),
                'user_agent' => (string) $request->userAgent(),
                'referer' => $request->headers->get('referer'),
            ]);
        }


        $products = $store->products()
            ->with('category')
            ->where('active', true)
            ->latest()
            ->get();

        $productCategories = $products
            ->pluck('category')
            ->filter()
            ->unique('id')
            ->sortBy('name')
            ->values();


        return view('stores.show', compact(
            'store',
            'products',
            'productCategories'
        ));
    }

    private function normalizePeruWhatsapp(?string $value): string
    {
        $digits = preg_replace('/[^0-9]/', '', (string) $value);

        if (strlen($digits) === 9 && str_starts_with($digits, '9')) {
            return '51' . $digits;
        }

        return $digits;
    }

    private function storeUploadedGalleryImages(Request $request, string $field, string $directory): array
    {
        $paths = [];

        foreach ($request->file($field, []) as $file) {
            $paths[] = $file->store($directory, 'public');
        }

        return $paths;
    }

    private function syncGalleryImages(
        array $currentImages,
        array $imagesToRemove,
        array $newImages,
        string $allowedPrefix,
        int $limit
    ): array {
        $imagesToRemove = array_values(array_intersect($imagesToRemove, $currentImages));

        foreach ($imagesToRemove as $image) {
            $this->deletePublicUpload($image, $allowedPrefix);
        }

        $keptImages = array_values(array_diff($currentImages, $imagesToRemove));
        $limitedImages = array_slice(array_merge($keptImages, $newImages), 0, $limit);

        foreach (array_diff($newImages, $limitedImages) as $unusedImage) {
            $this->deletePublicUpload($unusedImage, $allowedPrefix);
        }

        return $limitedImages;
    }
}
