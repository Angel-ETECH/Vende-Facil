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
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['bail', 'required', 'string', 'max:100'],
            'delivery_available' => ['nullable', 'boolean'],
            'logo' => [
                'nullable',
                File::image()->types(['jpg', 'jpeg', 'png', 'webp'])->max(2048),
                Rule::dimensions()->maxWidth(3000)->maxHeight(3000),
            ],
        ]);


        $logoPath = null;


        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')
                ->store('stores/logos', 'public');
        }

        $baseSlug = Str::slug($validated['name']) ?: 'tienda';

        Auth::user()->stores()->create([
            'name' => $validated['name'],
            'slug' => $baseSlug . '-' . Str::random(5),
            'logo' => $logoPath,
            'description' => $validated['description'] ?? null,
            'whatsapp' => $validated['whatsapp'],
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'],
            'delivery_available' => $request->boolean('delivery_available'),
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
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['bail', 'required', 'string', 'max:100'],
            'delivery_available' => ['nullable', 'boolean'],
            'logo' => [
                'nullable',
                File::image()->types(['jpg', 'jpeg', 'png', 'webp'])->max(2048),
                Rule::dimensions()->maxWidth(3000)->maxHeight(3000),
            ],
        ]);


        $logoPath = $store->logo;


        if ($request->hasFile('logo')) {

            $this->deletePublicUpload($store->logo, 'stores/logos/');


            $logoPath = $request->file('logo')
                ->store('stores/logos', 'public');
        }


        $store->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'whatsapp' => $validated['whatsapp'],
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'],
            'delivery_available' => $request->boolean('delivery_available'),
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
}
