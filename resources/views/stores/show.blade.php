@extends('layouts.public')

@php
    $storeDescription = $store->description
        ?: 'Explora el catálogo de '.$store->name.' en '.($store->city ?: 'Perú').' y compra directamente por WhatsApp en VendeFácil.';

    $storeImage = $store->logo
        ? asset('storage/'.$store->logo)
        : asset(config('seo.image'));

    $storeUrl = route('stores.show', ['store' => $store->slug]);
    $storeMapUrl = $store->mapUrl();
    $storeMapEmbedUrl = $store->mapEmbedUrl();
    $socialLinks = $store->socialLinks();

    $storeSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Store',
        'name' => $store->name,
        'description' => $storeDescription,
        'url' => $storeUrl,
        'image' => $storeImage,
        'telephone' => '+'.$store->whatsapp,
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => $store->address,
            'addressLocality' => $store->city ?: 'Perú',
            'addressCountry' => 'PE',
        ],
        'areaServed' => [
            '@type' => 'Country',
            'name' => 'Perú',
        ],
    ];

    if ($storeMapUrl) {
        $storeSchema['hasMap'] = $storeMapUrl;
    }

    if ($socialLinks) {
        $storeSchema['sameAs'] = array_values($socialLinks);
    }
@endphp

@section('title', $store->name . ' en ' . ($store->city ?: 'Perú') . ' | VendeFácil')

@section('description')
{{ $storeDescription }}
@endsection

@section('canonical', $storeUrl)

@section('image', $storeImage)

@push('structured-data')
    <script type="application/ld+json">
        {!! json_encode($storeSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_PRETTY_PRINT) !!}
    </script>
@endpush


@section('content')

{{-- ========================================================= --}}
{{-- CABECERA DE LA TIENDA --}}
{{-- ========================================================= --}}

<section
    class="relative overflow-hidden bg-[#fff3e5]"
>

    {{-- decoración del fondo --}}
    <div
        class="absolute -left-28 -top-24 h-72 w-72 rounded-full bg-[#ffd66b]/30"
    ></div>

    <div
        class="absolute -right-24 top-4 h-72 w-72 rounded-full bg-[#ffb8c8]/40"
    ></div>


    <div
        class="vf-container relative py-10 sm:py-14"
    >

        {{-- VOLVER --}}
        <a
            href="{{ route('home') }}"
            class="inline-flex items-center gap-2 text-sm font-bold text-[#7b6568] transition hover:text-[#ff6b57]"
        >

            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="2"
                stroke="currentColor"
                class="h-4 w-4"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"
                />
            </svg>

            Volver a explorar

        </a>


        {{-- TARJETA --}}
        <div
            class="relative mt-8 overflow-hidden rounded-[34px] border border-[#eaded5] bg-[#fffdf9] shadow-[0_20px_55px_rgba(91,53,43,.10)]"
        >

            {{-- pequeña línea decorativa, no divide la tarjeta --}}
            <div
                class="h-[7px] w-full bg-gradient-to-r from-[#ff7f6b] via-[#ffae78] to-[#ffd66b]"
            ></div>


            {{-- decoración interior --}}
            <div
                class="absolute -right-16 -top-20 h-56 w-56 rounded-full bg-[#ffd66b]/15"
            ></div>

            <div
                class="absolute right-[18%] -top-14 h-36 w-36 rounded-full bg-[#98dbc6]/15"
            ></div>


            <div
                class="relative grid gap-8 p-6 sm:p-8 lg:grid-cols-[1fr_auto] lg:items-center"
            >

                {{-- ================================================= --}}
                {{-- INFORMACIÓN DE LA TIENDA --}}
                {{-- ================================================= --}}

                <div
                    class="flex flex-col gap-6 sm:flex-row sm:items-center"
                >

                    {{-- LOGO --}}
                    <div class="flex-shrink-0">

                        @if($store->logo)

                            <img
                                src="{{ asset('storage/' . $store->logo) }}"
                                alt="{{ $store->name }}"
                                class="h-28 w-28 rounded-[28px] border-4 border-white bg-white object-cover shadow-lg"
                            >

                        @else

                            <div
                                class="flex h-28 w-28 items-center justify-center rounded-[28px] border-4 border-white bg-[#ffd66b] text-4xl font-black text-[#4b352e] shadow-lg"
                            >
                                {{ strtoupper(substr($store->name, 0, 1)) }}
                            </div>

                        @endif

                    </div>


                    {{-- DATOS --}}
                    <div class="min-w-0">

                        <div
                            class="flex flex-wrap items-center gap-3"
                        >

                            <h1
                                class="text-3xl font-black tracking-[-.045em] text-[#35272a] sm:text-[38px]"
                            >
                                {{ $store->name }}
                            </h1>


                            <span
                                class="inline-flex items-center gap-2 rounded-full bg-[#e7f5ef] px-3 py-1.5 text-xs font-black text-[#427764]"
                            >

                                <span
                                    class="h-2 w-2 rounded-full bg-[#69b59e]"
                                ></span>

                                Tienda activa

                            </span>

                            @if($store->isFeatured())
                                <span
                                    class="inline-flex items-center gap-2 rounded-full bg-[#fff3da] px-3 py-1.5 text-xs font-black text-[#806226]"
                                >
                                    Destacada
                                </span>
                            @endif

                            @if($store->isVerified())
                                <span
                                    class="inline-flex items-center gap-2 rounded-full bg-[#e7f5ef] px-3 py-1.5 text-xs font-black text-[#427764]"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="2"
                                        stroke="currentColor"
                                        class="h-3.5 w-3.5"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" />
                                    </svg>

                                    Tienda verificada
                                </span>
                            @endif

                        </div>


                        {{-- UBICACIÓN --}}
                        <div
                            class="mt-3 flex items-center gap-2 text-sm font-semibold text-[#8d787a]"
                        >

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.8"
                                stroke="currentColor"
                                class="h-4 w-4 flex-shrink-0 text-[#ff6b57]"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 21s6.75-4.2 6.75-10.5a6.75 6.75 0 1 0-13.5 0C5.25 16.8 12 21 12 21Z"
                                />

                                <circle
                                    cx="12"
                                    cy="10.5"
                                    r="2"
                                />
                            </svg>

                            {{ $store->city ? $store->city . ', Perú' : 'Perú' }}
                            @if($store->address)
                                · {{ $store->address }}
                            @endif

                        </div>

                        @if($storeMapUrl)
                            <a
                                href="{{ $storeMapUrl }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="mt-3 inline-flex items-center gap-2 rounded-full bg-[#fff0db] px-4 py-2 text-xs font-black text-[#a36c53] transition hover:bg-[#ffe2bd]"
                            >
                                Ver ubicación en Google Maps
                            </a>
                        @endif


                        <div class="mt-3 flex flex-wrap gap-2">
                            <span
                                class="inline-flex rounded-full bg-[#fff0db] px-3 py-1.5 text-xs font-black text-[#a36c53]"
                            >
                                Atención en Perú
                            </span>

                            <span
                                class="inline-flex rounded-full px-3 py-1.5 text-xs font-black {{ $store->delivery_available ? 'bg-[#e7f5ef] text-[#427764]' : 'bg-[#eee5df] text-[#8b7678]' }}"
                            >
                                {{ $store->delivery_available ? 'Hace delivery' : 'Sin delivery' }}
                            </span>

                            @if(! $store->delivery_available && $storeMapUrl)
                                <span
                                    class="inline-flex rounded-full bg-[#eef4ff] px-3 py-1.5 text-xs font-black text-[#496d9d]"
                                >
                                    Compra en tienda física
                                </span>
                            @endif

                            @if($store->opening_hours)
                                <span
                                    class="inline-flex rounded-full bg-white px-3 py-1.5 text-xs font-black text-[#6f5b5e]"
                                >
                                    {{ $store->opening_hours }}
                                </span>
                            @endif
                        </div>

                        @if($socialLinks)
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach($socialLinks as $label => $url)
                                    <a
                                        href="{{ $url }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-flex rounded-full border border-[#eaded5] bg-white px-3 py-1.5 text-xs font-black text-[#6f5b5e] transition hover:border-[#ffb7a9] hover:text-[#ff604c]"
                                    >
                                        {{ $label }}
                                    </a>
                                @endforeach
                            </div>
                        @endif


                        {{-- DESCRIPCIÓN --}}
                        <p
                            class="mt-5 max-w-2xl text-base leading-7 text-[#78666a]"
                        >
                            {{ $store->description ?: 'Descubre todos los productos disponibles en esta tienda y conversa directamente con el negocio.' }}
                        </p>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- ACCIONES --}}
                {{-- ================================================= --}}

                <div
                    class="flex flex-col gap-3 sm:flex-row lg:min-w-[260px] lg:flex-col"
                >

                    {{-- PRODUCTOS --}}
                    <div
                        class="flex items-center gap-3 rounded-[20px] border border-[#f0e1d5] bg-[#fff6ea] px-5 py-4"
                    >

                        <div
                            class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-[14px] bg-[#ffd66b] text-[#604a22]"
                        >

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.8"
                                stroke="currentColor"
                                class="h-5 w-5"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6.75 4.5h10.5l1.5 15h-13.5l1.5-15ZM9 8.25a3 3 0 0 0 6 0"
                                />
                            </svg>

                        </div>


                        <div>

                            <div
                                class="text-xl font-black leading-none text-[#443438]"
                            >
                                {{ $products->count() }}
                            </div>

                            <div
                                class="mt-1 text-xs font-bold text-[#947f80]"
                            >
                                {{ $products->count() == 1 ? 'producto disponible' : 'productos disponibles' }}
                            </div>

                        </div>

                    </div>


                    {{-- WHATSAPP --}}
                    <a
                        href="{{ route('stores.whatsapp', $store) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="flex items-center justify-center gap-2 rounded-[20px] bg-[#e6f5ef] px-5 py-4 font-black text-[#36745f] transition hover:bg-[#d6eee5]"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            class="h-5 w-5"
                        >
                            <path d="M12.04 2a9.84 9.84 0 0 0-8.41 14.94L2 22l5.2-1.62A9.98 9.98 0 1 0 12.04 2Zm0 18.15a8.2 8.2 0 0 1-4.18-1.14l-.3-.18-3.08.96.94-3-.2-.31A8.17 8.17 0 1 1 12.04 20.15Zm4.5-6.12c-.25-.12-1.47-.72-1.7-.8-.23-.08-.4-.12-.56.13-.17.24-.65.8-.79.97-.14.16-.29.18-.53.06-.25-.13-1.05-.39-2-1.23-.74-.65-1.23-1.46-1.38-1.7-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.15.16-.25.25-.41.08-.17.04-.31-.02-.44-.06-.12-.56-1.36-.77-1.87-.2-.49-.41-.42-.57-.43h-.48c-.16 0-.43.06-.65.31-.23.25-.86.84-.86 2.04 0 1.2.88 2.36 1 2.52.12.16 1.72 2.62 4.17 3.68.58.25 1.04.4 1.4.51.59.19 1.12.16 1.54.1.47-.07 1.47-.6 1.67-1.18.21-.58.21-1.08.15-1.18-.06-.1-.23-.16-.48-.28Z"/>
                        </svg>

                        Consultar por WhatsApp

                    </a>

                    @if($storeMapUrl)
                        <a
                            href="{{ $storeMapUrl }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="flex items-center justify-center gap-2 rounded-[20px] bg-[#fff3da] px-5 py-4 font-black text-[#806226] transition hover:bg-[#ffe9ba]"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.8"
                                stroke="currentColor"
                                class="h-5 w-5"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 21s6.75-4.2 6.75-10.5a6.75 6.75 0 1 0-13.5 0C5.25 16.8 12 21 12 21Z"
                                />
                                <circle cx="12" cy="10.5" r="2"/>
                            </svg>

                            Cómo llegar
                        </a>
                    @endif

                    <button
                        type="button"
                        x-data="{ copied: false, url: @js($storeUrl) }"
                        @click="navigator.clipboard.writeText(url); copied = true; setTimeout(() => copied = false, 1800)"
                        class="flex items-center justify-center gap-2 rounded-[20px] border border-[#eaded5] bg-white px-5 py-4 font-black text-[#6f5b5e] transition hover:bg-[#fff8f1]"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.8"
                            stroke="currentColor"
                            class="h-5 w-5"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5h-1.5A2.25 2.25 0 0 0 4.5 9.75v8.25A2.25 2.25 0 0 0 6.75 20.25h8.25A2.25 2.25 0 0 0 17.25 18v-1.5M9 3.75h9.75A1.5 1.5 0 0 1 20.25 5.25V15A1.5 1.5 0 0 1 18.75 16.5H9A1.5 1.5 0 0 1 7.5 15V5.25A1.5 1.5 0 0 1 9 3.75Z" />
                        </svg>

                        <span x-text="copied ? 'URL copiada' : 'Compartir tienda'"></span>
                    </button>

                </div>

            </div>

        </div>

    </div>

</section>


@if(! empty($store->gallery_images))
    <section class="border-y border-[#ebded5] bg-[#fffaf2] py-12">
        <div class="vf-container">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <span class="inline-flex rounded-full bg-[#98dbc6] px-4 py-1.5 text-[11px] font-black uppercase tracking-[.13em] text-[#315c4d]">
                        Confianza
                    </span>

                    <h2 class="mt-4 text-2xl font-black tracking-[-.035em] text-[#35272a]">
                        Mira más de {{ $store->name }}
                    </h2>
                </div>

                <p class="max-w-xl text-sm leading-6 text-[#806d70]">
                    Fotos del local, ambiente o productos para que compres con más seguridad.
                </p>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($store->gallery_images as $galleryImage)
                    <img
                        src="{{ asset('storage/' . $galleryImage) }}"
                        alt="Foto de {{ $store->name }}"
                        class="h-56 w-full rounded-[26px] border border-[#eaded5] object-cover shadow-[0_12px_30px_rgba(91,53,43,.07)]"
                        loading="lazy"
                    >
                @endforeach
            </div>
        </div>
    </section>
@endif


@if($storeMapEmbedUrl)
    <section class="border-y border-[#ebded5] bg-[#fffaf2] py-12">
        <div class="vf-container">
            <div class="grid gap-6 lg:grid-cols-[.8fr_1.2fr] lg:items-stretch">
                <div class="rounded-[30px] border border-[#eaded5] bg-white p-6 shadow-[0_16px_40px_rgba(91,53,43,.07)]">
                    <span class="inline-flex rounded-full bg-[#fff3da] px-4 py-1.5 text-[11px] font-black uppercase tracking-[.13em] text-[#806226]">
                        Ubicación
                    </span>

                    <h2 class="mt-4 text-2xl font-black tracking-[-.035em] text-[#35272a]">
                        Visita {{ $store->name }}
                    </h2>

                    <p class="mt-3 text-sm leading-6 text-[#806d70]">
                        {{ $store->delivery_available
                            ? 'Puedes coordinar delivery o acercarte al negocio según lo que acuerdes por WhatsApp.'
                            : 'Esta tienda no ofrece delivery por ahora, así que su ubicación ayuda a que puedas acercarte directamente.'
                        }}
                    </p>

                    <div class="mt-5 rounded-[22px] bg-[#fff8f1] px-4 py-4">
                        <p class="text-xs font-black uppercase tracking-[.1em] text-[#a08a89]">
                            Dirección
                        </p>
                        <p class="mt-2 text-sm font-black leading-6 text-[#514043]">
                            {{ $store->locationLabel() }}
                        </p>
                    </div>

                    <a
                        href="{{ $storeMapUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="mt-5 inline-flex w-full items-center justify-center rounded-[18px] bg-[#442d35] px-5 py-3.5 text-sm font-black text-white transition hover:bg-[#ff6b57]"
                    >
                        Abrir ruta en Google Maps
                    </a>
                </div>

                <div class="overflow-hidden rounded-[30px] border border-[#eaded5] bg-white shadow-[0_16px_40px_rgba(91,53,43,.07)]">
                    <iframe
                        src="{{ $storeMapEmbedUrl }}"
                        title="Mapa de {{ $store->name }}"
                        class="h-[320px] w-full sm:h-[380px]"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>
                </div>
            </div>
        </div>
    </section>
@endif


{{-- ========================================================= --}}
{{-- CATÁLOGO --}}
{{-- ========================================================= --}}
<section
    class="bg-[#fffaf2] py-12 sm:py-14"
    x-data="{
        productSearch: '',
        selectedCategory: '',
        onlyAvailable: false,
        detailModalOpen: false,
        selectedProduct: {
            name: '',
            description: '',
            category: '',
            price: '',
            stock: 0,
            image: null,
            galleryImages: [],
            mapUrl: @js($storeMapUrl),
            whatsappUrl: '',
        },
        products: @js($products->map(fn ($product) => [
            'name' => $product->name,
            'description' => $product->description ?: '',
            'category' => (string) $product->category_id,
            'stock' => (int) $product->stock,
        ])->values()),
        matchesProduct(name, description, category, stock) {
            const text = (name + ' ' + description).toLowerCase();
            const query = this.productSearch.toLowerCase().trim();

            return (!query || text.includes(query))
                && (!this.selectedCategory || this.selectedCategory === category)
                && (!this.onlyAvailable || stock > 0);
        },
        get visibleProductsCount() {
            return this.products.filter((product) => this.matchesProduct(
                product.name,
                product.description,
                product.category,
                product.stock,
            )).length;
        },
        clearFilters() {
            this.productSearch = '';
            this.selectedCategory = '';
            this.onlyAvailable = false;
        },
        openDetailModal(product) {
            this.selectedProduct = product;
            this.detailModalOpen = true;
        },
        closeDetailModal() {
            this.detailModalOpen = false;
        },
    }"
>

    <div class="vf-container">

        {{-- ENCABEZADO --}}
        <div
            class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between"
        >

            <div>

                <span
                    class="inline-flex rounded-full bg-[#98dbc6] px-4 py-1.5 text-[11px] font-black uppercase tracking-[.13em] text-[#315e52]"
                >
                    Catálogo
                </span>

                <h2
                    class="mt-4 text-3xl font-black tracking-[-.04em] text-[#35272a] sm:text-4xl"
                >
                    Productos disponibles
                </h2>

                <p
                    class="mt-2 max-w-xl text-base leading-7 text-[#78666a]"
                >
                    Encuentra algo que te guste y conversa directamente
                    con la tienda para hacer tu pedido.
                </p>

            </div>

        </div>


        @if($products->isEmpty())

            <div
                class="mt-8 rounded-[26px] border-2 border-dashed border-[#dcc9bb] bg-white px-6 py-12 text-center"
            >

                <div
                    class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-[#fff0e4] text-[#ff6b57]"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.8"
                        stroke="currentColor"
                        class="h-6 w-6"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6.75 4.5h10.5l1.5 15h-13.5l1.5-15ZM9 8.25a3 3 0 0 0 6 0"
                        />
                    </svg>
                </div>

                <h3
                    class="mt-4 text-xl font-black text-[#3c2d30]"
                >
                    Todavía no hay productos
                </h3>

                <p
                    class="mt-2 text-sm text-[#806d70]"
                >
                    Esta tienda está preparando su catálogo.
                </p>

            </div>

        @else

            <div
                class="mt-8 rounded-[26px] border border-[#eaded5] bg-white p-4 shadow-[0_16px_40px_rgba(91,53,43,.07)]"
            >

                <div
                    class="grid gap-3 lg:grid-cols-[minmax(0,1.2fr)_minmax(190px,.55fr)_auto]"
                >

                    <div class="relative">

                        <div
                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-[#a99492]"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                                stroke="currentColor"
                                class="h-5 w-5"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="m21 21-4.5-4.5m2.25-5.25a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z"
                                />
                            </svg>
                        </div>


                        <input
                            type="text"
                            x-model="productSearch"
                            placeholder="Buscar dentro de esta tienda..."
                            class="h-14 w-full rounded-[18px] border border-[#eaded5] bg-[#fffdf9] pl-12 pr-4 text-sm font-bold text-[#4b3a3d] placeholder:text-[#aa9491] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                        >

                    </div>


                    <div
                        class="relative"
                        x-data="{ open: false, label: 'Todas las categorías' }"
                        @click.outside="open = false"
                    >
                        <button
                            type="button"
                            @click="open = !open"
                            class="flex h-14 min-w-[220px] items-center justify-between gap-3 rounded-[18px] border border-[#eaded5] bg-[#fffdf9] px-4 text-left text-sm font-black text-[#4b3a3d] shadow-sm transition hover:border-[#ffd2c8] hover:bg-white focus:border-[#ff8b78] focus:outline-none focus:ring-2 focus:ring-[#ff8b78]/25"
                        >
                            <span class="truncate" x-text="label"></span>

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                                stroke="currentColor"
                                class="h-4 w-4 flex-shrink-0 text-[#7e6a6d] transition"
                                :class="{ 'rotate-180': open }"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="m6 9 6 6 6-6"
                                />
                            </svg>
                        </button>

                        <div
                            x-show="open"
                            x-cloak
                            x-transition.origin.top
                            class="absolute left-0 right-0 z-40 mt-2 max-h-72 overflow-y-auto rounded-[18px] border border-[#eaded5] bg-white p-2 shadow-[0_18px_45px_rgba(91,53,43,.16)]"
                        >
                            <button
                                type="button"
                                @click="selectedCategory = ''; label = 'Todas las categorías'; open = false"
                                class="flex w-full items-center justify-between rounded-[14px] px-4 py-3 text-left text-sm font-black transition hover:bg-[#fff3e8]"
                                :class="selectedCategory === '' ? 'bg-[#ff6b57] text-white' : 'text-[#4b3a3d]'"
                            >
                                Todas las categorías
                                <span x-show="selectedCategory === ''">✓</span>
                            </button>

                            @foreach($productCategories as $category)
                                <button
                                    type="button"
                                    @click="selectedCategory = @js((string) $category->id); label = @js($category->name); open = false"
                                    class="mt-1 flex w-full items-center justify-between rounded-[14px] px-4 py-3 text-left text-sm font-black transition hover:bg-[#fff3e8]"
                                    :class="selectedCategory === @js((string) $category->id) ? 'bg-[#ff6b57] text-white' : 'text-[#4b3a3d]'"
                                >
                                    {{ $category->name }}
                                    <span x-show="selectedCategory === @js((string) $category->id)">✓</span>
                                </button>
                            @endforeach
                        </div>
                    </div>


                    <label
                        class="flex h-14 cursor-pointer items-center justify-center gap-3 rounded-[18px] border border-[#eaded5] bg-[#fffdf9] px-5 transition hover:bg-[#fff8f0]"
                    >
                        <input
                            type="checkbox"
                            x-model="onlyAvailable"
                            class="h-5 w-5 rounded border-[#d8c8bf] text-[#ff6b57] focus:ring-[#ff8b78]"
                        >

                        <span class="whitespace-nowrap text-sm font-black text-[#4b3a3d]">
                            Solo disponibles
                        </span>
                    </label>

                </div>

            </div>

            <div
                class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
            >

                @foreach($products as $product)

                    <article
                        class="vf-card vf-product-image overflow-hidden rounded-[24px] border border-[#eaded5] bg-white"
                        x-show="matchesProduct(
                            @js($product->name),
                            @js($product->description ?: ''),
                            @js((string) $product->category_id),
                            {{ (int) $product->stock }}
                        )"
                        x-transition
                    >

                        {{-- IMAGEN --}}
                        <div
                            class="relative h-[210px] overflow-hidden bg-[#f6e8de]"
                        >

                            @if($product->image)

                                <img
                                    src="{{ asset('storage/' . $product->image) }}"
                                    alt="{{ $product->name }}"
                                    class="h-full w-full object-cover"
                                >

                            @else

                                <div
                                    class="flex h-full items-center justify-center text-[#c38f78]"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="h-12 w-12"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M3.75 6.75h16.5v12H3.75v-12Zm3-3h10.5l3 3H3.75l3-3Z"
                                        />
                                    </svg>
                                </div>

                            @endif


                            @if($product->stock > 0)

                                <span
                                    class="absolute left-3 top-3 inline-flex items-center gap-2 rounded-full bg-white/90 px-3 py-1.5 text-[11px] font-black text-[#467a69] shadow-sm backdrop-blur"
                                >
                                    <span
                                        class="h-2 w-2 rounded-full bg-[#69b59e]"
                                    ></span>

                                    Disponible
                                </span>

                            @else

                                <span
                                    class="absolute left-3 top-3 rounded-full bg-[#442d35] px-3 py-1.5 text-[11px] font-black text-white"
                                >
                                    Agotado
                                </span>

                            @endif

                        </div>


                        {{-- CONTENIDO --}}
                        <div class="p-4">

                            <div class="mb-3 flex flex-wrap gap-2">
                                <span
                                    class="rounded-full bg-[#fff3da] px-3 py-1.5 text-[11px] font-black text-[#806226]"
                                >
                                    {{ $product->category?->name ?: 'Sin categoría' }}
                                </span>
                            </div>


                            <h3
                                class="truncate text-[18px] font-black tracking-[-.025em] text-[#35272a]"
                            >
                                {{ $product->name }}
                            </h3>


                            <p
                                class="mt-2 line-clamp-2 min-h-[40px] text-sm leading-5 text-[#806d70]"
                            >
                                {{ $product->description ?: 'Producto disponible en esta tienda.' }}
                            </p>


                            <div
                                class="mt-4 flex items-end justify-between gap-3"
                            >

                                <div>

                                    <p
                                        class="text-[10px] font-bold uppercase tracking-[.12em] text-[#aa9094]"
                                    >
                                        Precio
                                    </p>

                                    <p
                                        class="mt-1 text-[23px] font-black text-[#ff604c]"
                                    >
                                        S/ {{ number_format($product->price, 2) }}
                                    </p>

                                </div>


                                @if($product->stock > 0)

                                    <div
                                        class="rounded-full bg-[#fff3da] px-3 py-1.5 text-[11px] font-black text-[#806226]"
                                    >
                                        {{ $product->stock }} disponibles
                                    </div>

                                @endif

                            </div>


                            @if($product->stock > 0)

                                <div class="mt-4 grid grid-cols-2 gap-2">

                                    <button
                                        type="button"
                                        class="flex w-full items-center justify-center rounded-[14px] border border-[#eaded5] bg-[#fffdf9] px-3 py-3 text-sm font-black text-[#6f5b5e] transition hover:bg-[#f8eee7]"
                                        @click="openDetailModal({
                                            name: @js($product->name),
                                            description: @js($product->description ?: 'Producto disponible en esta tienda.'),
                                            category: @js($product->category?->name ?: 'Sin categoría'),
                                            price: @js('S/ ' . number_format($product->price, 2)),
                                            stock: {{ (int) $product->stock }},
                                            image: @js($product->image ? asset('storage/' . $product->image) : null),
                                            galleryImages: @js(collect($product->gallery_images ?? [])->map(fn ($image) => asset('storage/' . $image))->values()),
                                            mapUrl: @js($storeMapUrl),
                                            whatsappUrl: @js(route('products.whatsapp', [$store, $product])),
                                        })"
                                    >
                                        Detalles
                                    </button>


                                    <a
                                        href="{{ route('products.whatsapp', [$store, $product]) }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="flex w-full items-center justify-center gap-2 rounded-[14px] bg-[#25D366] px-3 py-3 text-sm font-black text-white transition hover:bg-[#20bd5a]"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24"
                                            fill="currentColor"
                                            class="h-4 w-4"
                                        >
                                            <path d="M12.04 2a9.84 9.84 0 0 0-8.41 14.94L2 22l5.2-1.62A9.98 9.98 0 1 0 12.04 2Zm0 18.15a8.2 8.2 0 0 1-4.18-1.14l-.3-.18-3.08.96.94-3-.2-.31A8.17 8.17 0 1 1 12.04 20.15Zm4.5-6.12c-.25-.12-1.47-.72-1.7-.8-.23-.08-.4-.12-.56.13-.17.24-.65.8-.79.97-.14.16-.29.18-.53.06-.25-.13-1.05-.39-2-1.23-.74-.65-1.23-1.46-1.38-1.7-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.15.16-.25.25-.41.08-.17.04-.31-.02-.44-.06-.12-.56-1.36-.77-1.87-.2-.49-.41-.42-.57-.43h-.48c-.16 0-.43.06-.65.31-.23.25-.86.84-.86 2.04 0 1.2.88 2.36 1 2.52.12.16 1.72 2.62 4.17 3.68.58.25 1.04.4 1.4.51.59.19 1.12.16 1.54.1.47-.07 1.47-.6 1.67-1.18.21-.58.21-1.08.15-1.18-.06-.1-.23-.16-.48-.28Z"/>
                                        </svg>

                                        Pedir
                                    </a>

                                </div>

                            @else

                                <div class="mt-4 grid grid-cols-2 gap-2">

                                    <button
                                        type="button"
                                        class="flex w-full items-center justify-center rounded-[14px] border border-[#eaded5] bg-[#fffdf9] px-3 py-3 text-sm font-black text-[#6f5b5e] transition hover:bg-[#f8eee7]"
                                        @click="openDetailModal({
                                            name: @js($product->name),
                                            description: @js($product->description ?: 'Producto disponible en esta tienda.'),
                                            category: @js($product->category?->name ?: 'Sin categoría'),
                                            price: @js('S/ ' . number_format($product->price, 2)),
                                            stock: {{ (int) $product->stock }},
                                            image: @js($product->image ? asset('storage/' . $product->image) : null),
                                            galleryImages: @js(collect($product->gallery_images ?? [])->map(fn ($image) => asset('storage/' . $image))->values()),
                                            mapUrl: @js($storeMapUrl),
                                            whatsappUrl: @js(route('products.whatsapp', [$store, $product])),
                                        })"
                                    >
                                        Detalles
                                    </button>


                                    <button
                                        type="button"
                                        disabled
                                        class="w-full cursor-not-allowed rounded-[14px] bg-[#eee5df] px-3 py-3 text-sm font-black text-[#ab9795]"
                                    >
                                        Agotado
                                    </button>

                                </div>

                            @endif

                        </div>

                    </article>

                @endforeach

            </div>

            <div
                x-cloak
                x-show="detailModalOpen"
                x-transition.opacity
                class="fixed inset-0 z-50 flex items-end justify-center bg-[#2c2023]/50 px-4 py-6 backdrop-blur-sm sm:items-center"
                @keydown.escape.window="closeDetailModal()"
            >

                <div
                    x-show="detailModalOpen"
                    x-transition
                    class="w-full max-w-lg overflow-hidden rounded-[30px] border border-[#eaded5] bg-white shadow-2xl"
                    @click.outside="closeDetailModal()"
                >

                    <template x-if="selectedProduct.image">
                        <img
                            :src="selectedProduct.image"
                            alt="Producto"
                            class="h-56 w-full object-cover"
                        >
                    </template>

                    <template x-if="!selectedProduct.image">
                        <div class="flex h-44 items-center justify-center bg-[#f6e8de] text-[#bf8d77]">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="h-12 w-12"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3.75 6.75h16.5v12H3.75v-12Zm3-3h10.5l3 3H3.75l3-3Z"
                                />
                            </svg>
                        </div>
                    </template>


                    <div class="p-6">

                        <template x-if="selectedProduct.galleryImages.length">
                            <div class="-mt-1 mb-5 grid grid-cols-3 gap-2">
                                <template x-for="galleryImage in selectedProduct.galleryImages" :key="galleryImage">
                                    <img
                                        :src="galleryImage"
                                        alt="Foto extra del producto"
                                        class="h-20 w-full rounded-[16px] object-cover"
                                    >
                                </template>
                            </div>
                        </template>

                        <div class="flex items-start justify-between gap-4">

                            <div class="min-w-0">
                                <span
                                    class="inline-flex rounded-full bg-[#fff3da] px-3 py-1.5 text-xs font-black text-[#806226]"
                                    x-text="selectedProduct.category"
                                ></span>

                                <h3
                                    class="mt-3 text-2xl font-black tracking-[-.035em] text-[#35272a]"
                                    x-text="selectedProduct.name"
                                ></h3>
                            </div>


                            <button
                                type="button"
                                class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-[#f8eee7] text-[#6f5b5e]"
                                @click="closeDetailModal()"
                            >
                                x
                            </button>

                        </div>


                        <p
                            class="mt-4 text-sm leading-6 text-[#806d70]"
                            x-text="selectedProduct.description"
                        ></p>


                        <div class="mt-5 flex items-end justify-between gap-4 rounded-[20px] bg-[#fff8f1] px-4 py-4">
                            <div>
                                <p class="text-xs font-black uppercase tracking-[.1em] text-[#aa9094]">
                                    Precio
                                </p>
                                <p
                                    class="mt-1 text-2xl font-black text-[#ff604c]"
                                    x-text="selectedProduct.price"
                                ></p>
                            </div>

                            <span class="rounded-full bg-[#fff3da] px-3 py-1.5 text-xs font-black text-[#806226]">
                                Stock <span x-text="selectedProduct.stock"></span>
                            </span>
                        </div>


                        <div class="mt-5 grid gap-3 sm:grid-cols-2">
                            <button
                                type="button"
                                class="h-12 rounded-[16px] border border-[#e4d6cd] px-5 text-sm font-black text-[#78666a] transition hover:bg-[#f8eee7]"
                                @click="closeDetailModal()"
                            >
                                Seguir viendo
                            </button>

                            <a
                                :href="selectedProduct.whatsappUrl"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex h-12 items-center justify-center rounded-[16px] bg-[#25D366] px-5 text-sm font-black text-white transition hover:bg-[#20bd5a]"
                            >
                                Pedir por WhatsApp
                            </a>

                            <template x-if="selectedProduct.mapUrl">
                                <a
                                    :href="selectedProduct.mapUrl"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex h-12 items-center justify-center rounded-[16px] bg-[#fff3da] px-5 text-sm font-black text-[#806226] transition hover:bg-[#ffe9ba] sm:col-span-2"
                                >
                                    Ver ubicación de la tienda
                                </a>
                            </template>
                        </div>

                    </div>

                </div>

            </div>


            <div
                x-cloak
                x-show="visibleProductsCount === 0"
                x-transition
                class="mt-8 rounded-[26px] border-2 border-dashed border-[#dcc9bb] bg-white px-6 py-12 text-center"
            >

                <div
                    class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-[#fff0e4] text-[#ff6b57]"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.8"
                        stroke="currentColor"
                        class="h-6 w-6"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m21 21-4.5-4.5m2.25-5.25a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z"
                        />
                    </svg>
                </div>


                <h3 class="mt-4 text-xl font-black text-[#3c2d30]">
                    No encontramos productos con esos filtros
                </h3>


                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-[#806d70]">
                    Prueba con otra palabra, cambia la categoría o mira todos los productos de la tienda.
                </p>


                <button
                    type="button"
                    class="mt-5 inline-flex rounded-full bg-[#ff6b57] px-6 py-3 text-sm font-black text-white transition hover:bg-[#ed5542]"
                    @click="clearFilters()"
                >
                    Limpiar filtros
                </button>

            </div>

        @endif

    </div>

</section>


{{-- ========================================================= --}}
{{-- CTA FINAL --}}
{{-- ========================================================= --}}
<section class="border-t border-[#ebded5] bg-[#f4eadf]">
    <div class="vf-container py-12">

        <div class="flex flex-col gap-6 rounded-[30px] bg-[#442d35] px-7 py-8 text-white sm:flex-row sm:items-center sm:justify-between sm:px-9">

            <div>
                <p class="text-sm font-bold uppercase tracking-[.14em] text-[#f5c75e]">
                    Compra directa
                </p>

                <h3 class="mt-2 text-2xl font-black tracking-[-.03em]">
                    ¿Tienes una duda sobre algún producto?
                </h3>

                <p class="mt-2 text-sm leading-6 text-[#d8c5cb]">
                    Habla directamente con {{ $store->name }} mediante WhatsApp.
                </p>
            </div>

            <a
                href="{{ route('stores.whatsapp', $store) }}"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex flex-shrink-0 items-center justify-center gap-2 rounded-full bg-[#f5c75e] px-6 py-3.5 font-black text-[#442d35] transition hover:scale-[1.02]"
            >
                Contactar tienda
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="2"
                    stroke="currentColor"
                    class="h-4 w-4"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M5 12h14m-5-5 5 5-5 5"
                    />
                </svg>
            </a>

        </div>

    </div>
</section>

@endsection
