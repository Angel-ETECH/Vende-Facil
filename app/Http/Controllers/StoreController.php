<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\StoreVisit;
use App\Support\SafePublicStorage;
use App\Support\OptimizedImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class StoreController extends Controller
{
    use SafePublicStorage;

    public function create()
    {
        if (Auth::user()->stores()->exists()) { return redirect()->route('stores.edit'); }
        return view('stores.create');
    }


    public function store(Request $request)
    {
        if (Auth::user()->stores()->exists()) { return redirect()->route('stores.edit'); }
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
            'location_url' => ['nullable', 'url:http,https', 'max:500'],
            'opening_hours' => ['nullable', 'string', 'max:255'],
            'facebook_url' => ['nullable', 'url:http,https', 'max:500'],
            'instagram_url' => ['nullable', 'url:http,https', 'max:500'],
            'tiktok_url' => ['nullable', 'url:http,https', 'max:500'],
            'website_url' => ['nullable', 'url:http,https', 'max:500'],
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


        $uploadedPaths = [];
        try {
            DB::transaction(function () use ($request, $validated, &$uploadedPaths) {
                $user = User::whereKey(Auth::id())->lockForUpdate()->firstOrFail();
                if ($user->stores()->exists()) {
                    throw ValidationException::withMessages(['name' => 'Ya tienes una tienda. Puedes editarla desde tu panel.']);
                }
                $logoPath = null;


                if ($request->hasFile('logo')) {
                    $logoPath = OptimizedImage::store($request->file('logo'), 'stores/logos');
                    $uploadedPaths[] = $logoPath;
                }

                $galleryImages = $this->storeUploadedGalleryImages($request, 'gallery_images', 'stores/gallery');
                $uploadedPaths = array_merge($uploadedPaths, $galleryImages);

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
            });
        } catch (\Throwable $exception) {
            foreach ($uploadedPaths as $path) { $this->deletePublicUpload($path, 'stores/'); }
            throw $exception;
        }


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
            'location_url' => ['nullable', 'url:http,https', 'max:500'],
            'opening_hours' => ['nullable', 'string', 'max:255'],
            'facebook_url' => ['nullable', 'url:http,https', 'max:500'],
            'instagram_url' => ['nullable', 'url:http,https', 'max:500'],
            'tiktok_url' => ['nullable', 'url:http,https', 'max:500'],
            'website_url' => ['nullable', 'url:http,https', 'max:500'],
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


        $uploadedPaths = [];
        try {
            $logoPath = $store->logo;
            $oldLogoPath = $store->logo;
            $oldGalleryImages = $store->gallery_images ?? [];


            if ($request->hasFile('logo')) {

                $logoPath = OptimizedImage::store($request->file('logo'), 'stores/logos');
                $uploadedPaths[] = $logoPath;
            }

            $newGalleryImages = $this->storeUploadedGalleryImages($request, 'gallery_images', 'stores/gallery');
            $uploadedPaths = array_merge($uploadedPaths, $newGalleryImages);

            $galleryImages = $this->syncGalleryImages(
                $store->gallery_images ?? [],
                $request->input('remove_gallery_images', []),
                $newGalleryImages,
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
        } catch (\Throwable $exception) {
            foreach ($uploadedPaths as $path) {
                $this->deletePublicUpload($path, 'stores/');
            }
            throw $exception;
        }

        if ($oldLogoPath !== $logoPath) { $this->deletePublicUpload($oldLogoPath, 'stores/logos/'); }
        foreach (array_diff($oldGalleryImages, $galleryImages) as $image) { $this->deletePublicUpload($image, 'stores/gallery/'); }


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
                'user_agent' => Str::substr((string) $request->userAgent(), 0, 1000),
                'referer' => $request->headers->has('referer') ? Str::substr((string) $request->headers->get('referer'), 0, 255) : null,
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

    private function normalizePeruWhatsapp(mixed $value): string
    {
        if (! is_string($value) && ! is_int($value)) {
            return '';
        }
        $digits = preg_replace('/[^0-9]/', '', (string) $value);

        if (strlen($digits) === 9 && str_starts_with($digits, '9')) {
            return '51' . $digits;
        }

        return $digits;
    }

}
