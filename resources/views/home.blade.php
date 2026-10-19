@extends('layouts.public')

@section('title', $pageTitle ?? 'VendeFácil | Marketplace de negocios locales en Perú')

@section('description')
{{ $pageDescription ?? 'Encuentra tiendas independientes, productos únicos y negocios locales con delivery en Perú. Compra directo por WhatsApp en VendeFácil.' }}
@endsection

@section('canonical', $canonicalUrl ?? route('home'))

@section('image', asset('images/seo/vendefacil-home.png'))

@push('head')
    <link rel="preload" as="image" href="{{ asset('images/hero/mercado-artesanal.png') }}">
@endpush


@section('content')

{{-- ========================================================= --}}
{{-- HERO --}}
{{-- ========================================================= --}}
<section class="vf-home-photo relative overflow-hidden bg-[#fff3e5]">
    <img class="vf-home-photo__image" src="{{ asset('images/hero/mercado-artesanal.png') }}" alt="" aria-hidden="true" fetchpriority="high" width="512" height="308">

    <div
        class="vf-container relative grid min-h-[620px] items-center gap-12 py-16 lg:grid-cols-[1.08fr_.92fr] lg:py-20"
    >

        {{-- ================================================= --}}
        {{-- TEXTO --}}
        {{-- ================================================= --}}
        <div>

            <div
                class="vf-hero-eyebrow"
            >

                <span class="vf-hero-eyebrow__rule" aria-hidden="true"></span>
                <span>Aquí siempre hay algo nuevo</span>

            </div>


            <h1
                class="vf-hero-title mt-7 max-w-[700px] text-[47px] font-black leading-[.98] text-[#35272a] sm:text-[64px] lg:text-[78px]"
            >
                <span class="vf-hero-title__line">Encuentra cosas</span>
                <span class="vf-hero-title__line text-[#ff604c]">que te hagan</span>
                <span class="vf-hero-title__line">
                    <span class="vf-hero-title__smile">sonreír<span class="vf-hero-title__period">.</span></span>
                </span>
            </h1>


            <p
                class="mt-7 max-w-[600px] text-[18px] leading-8 text-[#715d60]"
            >
                Descubre negocios con personalidad, productos únicos
                y compra directamente hablando con quien está detrás
                de cada tienda.
            </p>


            {{-- ================================================= --}}
            {{-- BUSCADOR --}}
            {{-- ================================================= --}}
            <form
                action="{{ route('home') }}"
                method="GET"
                class="mt-8 max-w-[660px]"
            >

                @if($selectedCategory)
                    <input
                        type="hidden"
                        name="categoria"
                        value="{{ $selectedCategory->slug }}"
                    >
                @endif


                <div
                    class="vf-search vf-hero-search flex flex-col gap-2 rounded-[24px] bg-white p-2 sm:flex-row"
                >

                    <div class="flex flex-1 items-center">

                        <div
                            class="ml-3 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-[#fff0db] text-[#f26452]"
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
                            name="buscar"
                            value="{{ $search }}"
                            placeholder="¿Qué estás buscando hoy?"
                            class="w-full border-0 bg-transparent px-3 py-4 text-base font-medium text-[#3e3033] placeholder:text-[#aa9491] focus:ring-0"
                        >

                    </div>


                    <button
                        type="submit"
                        class="rounded-[18px] bg-[#ff6b57] px-7 py-4 font-black text-white transition hover:bg-[#ec513d]"
                    >
                        Encontrar
                    </button>

                </div>


                <div
                    class="vf-hero-filters mt-3 grid gap-3 rounded-[22px] bg-white/70 p-3 backdrop-blur sm:grid-cols-[minmax(0,1fr)_auto] sm:items-end"
                >

                    <label
                        class="relative block"
                        x-data="{
                            open: false,
                            selected: @js($city ?: ''),
                            label: @js($city ?: 'Todo Perú'),
                            choose(value, text) {
                                this.selected = value;
                                this.label = text;
                                this.open = false;
                            }
                        }"
                        @click.outside="open = false"
                    >
                        <span
                            class="vf-hero-city-label mb-1 block text-xs font-black uppercase tracking-[.12em] text-[#8b7678]"
                        >
                            Ciudad
                        </span>

                        <input
                            type="hidden"
                            name="ciudad"
                            :value="selected"
                        >

                        <button
                            type="button"
                            @click="open = !open"
                            class="vf-hero-filter-control flex h-14 w-full items-center justify-between gap-3 rounded-[16px] border border-[#eaded5] bg-[#fffdf9] px-4 text-left text-sm font-black text-[#4b3a3d] shadow-sm transition hover:border-[#ffd2c8] hover:bg-white focus:border-[#ff8b78] focus:outline-none focus:ring-2 focus:ring-[#ff8b78]/25"
                        >
                            <span x-text="label"></span>

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                                stroke="currentColor"
                                class="h-4 w-4 text-[#7e6a6d] transition"
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
                            class="absolute left-0 right-0 z-40 mt-2 overflow-hidden rounded-[18px] border border-[#eaded5] bg-white p-2 shadow-[0_18px_45px_rgba(91,53,43,.16)]"
                        >
                            <button
                                type="button"
                                @click="choose('', 'Todo Perú')"
                                class="flex w-full items-center justify-between rounded-[14px] px-4 py-3 text-left text-sm font-black transition hover:bg-[#fff3e8]"
                                :class="selected === '' ? 'bg-[#ff6b57] text-white' : 'text-[#4b3a3d]'"
                            >
                                Todo Perú
                                <span x-show="selected === ''">✓</span>
                            </button>

                            @foreach($cities as $availableCity)
                                <button
                                    type="button"
                                    @click="choose(@js($availableCity), @js($availableCity))"
                                    class="mt-1 flex w-full items-center justify-between rounded-[14px] px-4 py-3 text-left text-sm font-black transition hover:bg-[#fff3e8]"
                                    :class="selected === @js($availableCity) ? 'bg-[#ff6b57] text-white' : 'text-[#4b3a3d]'"
                                >
                                    {{ $availableCity }}
                                    <span x-show="selected === @js($availableCity)">✓</span>
                                </button>
                            @endforeach
                        </div>
                    </label>


                    <label
                        class="vf-hero-filter-control flex h-14 cursor-pointer items-center justify-center gap-3 rounded-[16px] border border-[#eaded5] bg-[#fffdf9] px-5 transition hover:bg-[#fff8f0]"
                    >
                        <input
                            type="checkbox"
                            name="delivery"
                            value="1"
                            @checked($delivery)
                            class="h-5 w-5 rounded border-[#d8c8bf] text-[#ff6b57] focus:ring-[#ff8b78]"
                        >

                        <span class="whitespace-nowrap text-sm font-black text-[#4b3a3d]">
                            Solo con delivery
                        </span>
                    </label>

                </div>

            </form>


            <div class="vf-hero-active-filters">


                {{-- FILTROS ACTIVOS --}}
                @if($selectedCategory || $search || $city || $delivery)

                    <div class="mt-4 flex flex-wrap items-center gap-2">

                        <span
                            class="text-xs font-bold uppercase tracking-[.12em] text-[#967f7f]"
                        >
                            Filtros:
                        </span>


                        @if($selectedCategory)

                            <span
                                class="inline-flex items-center gap-2 rounded-full bg-[#ffe5df] px-3 py-1.5 text-xs font-black text-[#b14e3e]"
                            >
                                {{ $selectedCategory->name }}
                            </span>

                        @endif


                        @if($search)

                            <span
                                class="inline-flex items-center gap-2 rounded-full bg-[#e7f5ef] px-3 py-1.5 text-xs font-black text-[#427764]"
                            >
                                “{{ $search }}”
                            </span>

                        @endif


                        @if($city)

                            <span
                                class="inline-flex items-center gap-2 rounded-full bg-[#fff0db] px-3 py-1.5 text-xs font-black text-[#a36c53]"
                            >
                                {{ $city }}, Perú
                            </span>

                        @endif


                        @if($delivery)

                            <span
                                class="inline-flex items-center gap-2 rounded-full bg-[#e7f5ef] px-3 py-1.5 text-xs font-black text-[#427764]"
                            >
                                Con delivery
                            </span>

                        @endif


                        <a
                            href="{{ route('home') }}#productos"
                            class="text-xs font-black text-[#ff604c] hover:underline"
                        >
                            Limpiar filtros
                        </a>

                    </div>

                @endif

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- COLLAGE --}}
        {{-- ================================================= --}}
        <div class="relative hidden min-h-[500px] lg:block">

            @php
                $heroProduct = $products->first() ?? $featuredProducts->first();
            @endphp


            {{-- TARJETA PRINCIPAL --}}
            <div
                class="absolute left-[13%] top-[42px] w-[315px] rounded-[34px] bg-white p-4 shadow-[0_28px_60px_rgba(91,53,43,.16)]"
            >

                @if($heroProduct?->image)

                    <img
                        src="{{ asset('storage/' . $heroProduct->image) }}"
                        class="h-[290px] w-full rounded-[24px] object-cover"
                        alt="{{ $heroProduct->name }}"
                    >

                @else

                    <div
                        class="flex h-[290px] items-center justify-center rounded-[24px] bg-[#ffd9be] text-[#c77d5e]"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.6"
                            stroke="currentColor"
                            class="h-20 w-20"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3.75 6.75h16.5v12H3.75v-12Zm3-3h10.5l3 3H3.75l3-3Z"
                            />
                        </svg>

                    </div>

                @endif


                <div class="px-2 pb-2 pt-4">

                    <div
                        class="text-[13px] font-black uppercase tracking-[.12em] text-[#ff6b57]"
                    >
                        Mira lo nuevo
                    </div>

                    <div class="mt-1 text-xl font-black text-[#35272a]">
                        {{ $heroProduct?->name ?? 'Productos con personalidad' }}
                    </div>

                </div>

            </div>


            {{-- TARJETA WHATSAPP --}}
            <div
                class="absolute right-[18px] top-[120px] w-[170px] rounded-[24px] bg-[#98dbc6] p-5 shadow-xl"
            >

                <div
                    class="flex h-11 w-11 items-center justify-center rounded-full bg-white/50 text-[#355f52]"
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
                            d="M7.5 8.25h9m-9 3h6m-8.25 8.25 1.5-4.5a8.25 8.25 0 1 1 3 3l-4.5 1.5Z"
                        />
                    </svg>

                </div>

                <div
                    class="mt-4 text-[15px] font-black leading-tight text-[#244d41]"
                >
                    Habla directo con la tienda
                </div>

            </div>


            {{-- TARJETA TIENDAS --}}
            <div
                class="absolute bottom-[42px] right-[28px] rounded-[24px] bg-[#f3c95f] px-6 py-5 shadow-xl"
            >

                <div class="text-3xl font-black text-[#46361f]">
                    {{ $storesCount }}+
                </div>

                <div class="text-sm font-black text-[#735c28]">
                    tiendas para descubrir
                </div>

            </div>

        </div>

    </div>

</section>



{{-- ========================================================= --}}
{{-- PRODUCTOS --}}
{{-- ========================================================= --}}
<section
    id="productos"
    class="vf-discovery relative overflow-hidden py-20 {{ $products->count() > 0 && $products->count() < 3 ? 'vf-discovery--compact' : '' }}"
>

    <div class="vf-container relative">

        <div
            class="vf-discovery-heading flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between"
        >

            <div>

                @if($selectedCategory)

                    <span
                        class="inline-flex rounded-full bg-[#ffd66b] px-4 py-2 text-xs font-black uppercase tracking-[.13em] text-[#624f24]"
                    >
                        {{ $selectedCategory->name }}
                    </span>

                @elseif($search)

                    <span
                        class="inline-flex rounded-full bg-[#98dbc6] px-4 py-2 text-xs font-black uppercase tracking-[.13em] text-[#295b4d]"
                    >
                        Resultados
                    </span>

                @else

                    <span
                        class="inline-flex rounded-full bg-[#98dbc6] px-4 py-2 text-xs font-black uppercase tracking-[.13em] text-[#295b4d]"
                    >
                        Recién llegados
                    </span>

                @endif


                @if($selectedCategory && $search)

                    <h2 class="vf-section-title mt-5">
                        Resultados en
                        <span class="text-[#ff6b57]">
                            {{ $selectedCategory->name }}
                        </span>
                    </h2>

                    <p
                        class="mt-4 max-w-[600px] text-lg leading-7 text-[#78666a]"
                    >
                        Productos relacionados con “{{ $search }}”.
                    </p>


                @elseif($selectedCategory)

                    <h2 class="vf-section-title mt-5">
                        Explora
                        <span class="text-[#ff6b57]">
                            {{ $selectedCategory->name }}
                        </span>
                    </h2>

                    <p
                        class="mt-4 max-w-[600px] text-lg leading-7 text-[#78666a]"
                    >
                        Descubre los productos disponibles dentro de esta categoría.
                    </p>


                @elseif($search)

                    <h2 class="vf-section-title mt-5">
                        Resultados para
                        <span class="text-[#ff6b57]">
                            “{{ $search }}”
                        </span>
                    </h2>

                    <p
                        class="mt-4 max-w-[600px] text-lg leading-7 text-[#78666a]"
                    >
                        Encontramos productos relacionados con tu búsqueda.
                    </p>


                @else

                    <h2 class="vf-section-title mt-5">
                        Cosas lindas que

                        <span class="text-[#ff6b57]">
                            encontramos por aquí
                        </span>
                    </h2>

                    <p
                        class="mt-4 max-w-[600px] text-lg leading-7 text-[#78666a]"
                    >
                        Una pequeña selección de lo último que nuestros
                        negocios han publicado.
                    </p>

                @endif

                    <nav class="vf-category-directory" aria-label="Explorar categorías">
                        <a href="{{ route('home', array_filter(['buscar' => $search !== '' ? $search : null, 'ciudad' => $city ?: null, 'delivery' => $delivery ? 1 : null])) }}#productos" @if(!$selectedCategory) aria-current="page" @endif>
                            <span class="vf-category-directory__icon"><i data-lucide="shapes" aria-hidden="true"></i></span>
                            <span>Todos</span>
                            <i data-lucide="arrow-up-right" aria-hidden="true"></i>
                        </a>
                        @foreach($categories as $category)
                            @php
                                $categoryIcon = match($category->slug) {
                                    'comida' => 'utensils', 'moda' => 'shirt',
                                    'tecnologia' => 'monitor', 'belleza' => 'sparkles',
                                    'hogar' => 'house', 'regalos' => 'gift',
                                    'servicios' => 'wrench', default => 'shapes',
                                };
                            @endphp
                            <a href="{{ route('categories.show', array_filter(['category' => $category->slug, 'buscar' => $search !== '' ? $search : null, 'ciudad' => $city ?: null, 'delivery' => $delivery ? 1 : null])) }}#productos" @if($selectedCategory?->id === $category->id) aria-current="page" @endif>
                                <span class="vf-category-directory__icon"><i data-lucide="{{ $categoryIcon }}" aria-hidden="true"></i></span>
                                <span>{{ $category->name }}</span>
                                <i data-lucide="arrow-up-right" aria-hidden="true"></i>
                            </a>
                        @endforeach
                    </nav>
            </div>


            @if($selectedCategory || $search)

                <a
                    href="{{ route('home') }}#productos"
                    class="inline-flex w-fit items-center gap-2 rounded-full border border-[#e4d6cd] bg-white px-5 py-3 text-sm font-black text-[#6f5b5e] transition hover:bg-[#f8eee7]"
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
                            d="m6 6 12 12M18 6 6 18"
                        />
                    </svg>

                    Quitar filtros
                </a>

            @endif

        </div>


        {{-- ================================================= --}}
        {{-- RESULTADOS PRODUCTOS --}}
        {{-- ================================================= --}}
        @if($products->isEmpty())

            <div
                class="mt-11 rounded-[34px] border-2 border-dashed border-[#dcc9bb] bg-[#fffaf3] px-6 py-14 text-center"
            >

                <div
                    class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-white text-[#ff6b57] shadow-sm"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.8"
                        stroke="currentColor"
                        class="h-7 w-7"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m21 21-4.5-4.5m2.25-5.25a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z"
                        />
                    </svg>

                </div>


                <h3
                    class="mt-5 text-2xl font-black text-[#35272a]"
                >
                    No encontramos productos
                </h3>


                <p
                    class="mx-auto mt-2 max-w-lg text-[#826f71]"
                >

                    @if($selectedCategory && $search)

                        No encontramos productos de
                        <strong>{{ $selectedCategory->name }}</strong>
                        relacionados con “{{ $search }}”.

                    @elseif($selectedCategory)

                        Todavía no hay productos disponibles en
                        <strong>{{ $selectedCategory->name }}</strong>.

                    @elseif($search)

                        No encontramos productos relacionados con
                        “{{ $search }}”.

                    @else

                        Todavía no hay productos disponibles.

                    @endif

                </p>


                <a
                    href="{{ route('home') }}#productos"
                    class="mt-6 inline-flex rounded-full bg-[#ff6b57] px-6 py-3 font-black text-white transition hover:bg-[#ed5542]"
                >
                    Ver todos los productos
                </a>

            </div>


        @else

            <div class="vf-product-carousel mt-11" x-data="productCarousel" role="region" aria-label="Productos recientes" aria-roledescription="carrusel">
                <div class="vf-carousel-controls" x-show="hasOverflow" x-cloak>
                    <button type="button" class="vf-carousel-arrow" @click="move(-1)" :disabled="atStart" aria-label="Productos anteriores" title="Productos anteriores" aria-controls="product-carousel-track"><i data-lucide="chevron-left" aria-hidden="true"></i></button>
                    <button type="button" class="vf-carousel-arrow" @click="move(1)" :disabled="atEnd" aria-label="Siguientes productos" title="Siguientes productos" aria-controls="product-carousel-track"><i data-lucide="chevron-right" aria-hidden="true"></i></button>
                </div>
                <div id="product-carousel-track" class="vf-product-grid" x-ref="track" @scroll.debounce.50ms="update()" @keydown.left.prevent="move(-1)" @keydown.right.prevent="move(1)" tabindex="0" aria-label="Lista de productos">

                @foreach($products as $product)

                    <div class="vf-product-item relative min-w-0">
                    <div class="absolute right-3 top-3 z-10"><x-favorite-button type="product" :id="$product->id" /></div>

                    <a
                        href="{{ route('stores.show', $product->store) }}"
                        class="vf-card vf-catalog-card vf-product-image group overflow-hidden border bg-white"
                    >

                        {{-- IMAGEN --}}
                        <div
                            class="vf-catalog-card__media relative overflow-hidden"
                        >

                            @if($product->image)

                                <img
                                    src="{{ asset('storage/' . $product->image) }}"
                                    loading="lazy"
                                    decoding="async"
                                    alt="{{ $product->name }}"
                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.04]"
                                >

                            @else

                                <div
                                    class="flex h-full items-center justify-center bg-[#f8dfc7] text-[#bf8d77]"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.6"
                                        stroke="currentColor"
                                        class="h-16 w-16"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M3.75 6.75h16.5v12H3.75v-12Z"
                                        />
                                    </svg>

                                </div>

                            @endif


                            {{-- TIENDA --}}
                            <span
                                class="absolute left-4 top-4 max-w-[calc(100%-5rem)] truncate rounded-full bg-white/90 px-3 py-2 text-xs font-black text-[#5f474b] shadow-md backdrop-blur"
                            >
                                {{ $product->store->name }}
                            </span>


                        </div>


                        {{-- INFORMACIÓN --}}
                        <div class="vf-catalog-card__body p-5">

                            <div class="mb-3 flex flex-wrap gap-2">

                                <span
                                    class="rounded-full bg-[#fff3da] px-3 py-1.5 text-[11px] font-black text-[#806226]"
                                >
                                    {{ $product->category?->name ?: 'Sin categoría' }}
                                </span>


                                <span
                                    class="rounded-full px-3 py-1.5 text-[11px] font-black {{ $product->store->delivery_available ? 'bg-[#e7f5ef] text-[#427764]' : 'bg-[#eee5df] text-[#8b7678]' }}"
                                >
                                    {{ $product->store->delivery_available ? 'Delivery' : 'Sin delivery' }}
                                </span>

                            </div>


                            <h3
                                class="truncate text-[19px] font-black tracking-[-.02em] text-[#34272a]"
                            >
                                {{ $product->name }}
                            </h3>


                            <p
                                class="mt-2 line-clamp-2 min-h-[42px] text-sm leading-6 text-[#826d70]"
                            >
                                {{ $product->description ?: 'Algo bonito que quizá estabas buscando.' }}
                            </p>


                            <div
                                class="mt-3 flex items-center gap-2 text-xs font-bold text-[#9a8185]"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.8"
                                    stroke="currentColor"
                                    class="h-4 w-4 flex-shrink-0 text-[#f26452]"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 21s6.75-4.2 6.75-10.5a6.75 6.75 0 1 0-13.5 0C5.25 16.8 12 21 12 21Z"
                                    />
                                    <circle cx="12" cy="10.5" r="2"/>
                                </svg>

                                <span class="truncate">
                                    {{ $product->store->city ? $product->store->city . ', Perú' : 'Perú' }}
                                </span>
                            </div>


                            <div
                                class="mt-5 flex items-center justify-between"
                            >

                                <div>

                                    <div
                                        class="text-xs font-bold uppercase tracking-[.1em] text-[#aa9094]"
                                    >
                                        Precio
                                    </div>

                                    <div
                                        class="mt-1 text-2xl font-black text-[#ff604c]"
                                    >
                                        S/ {{ number_format($product->price, 2) }}
                                    </div>

                                </div>


                                <div class="flex flex-col items-end gap-2">
                                    @if($product->stock > 0)
                                        <span
                                            class="rounded-full bg-[#fff3da] px-3 py-1 text-[11px] font-black text-[#806226]"
                                        >
                                            {{ $product->stock }} disp.
                                        </span>
                                    @endif

                                <div
                                    class="flex h-11 w-11 items-center justify-center rounded-full bg-[#ffd66b] text-[#4e3b20] transition group-hover:scale-110"
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
                                            d="M5 12h14m-5-5 5 5-5 5"
                                        />
                                    </svg>

                                </div>

                                </div>

                            </div>

                        </div>

                    </a>
                    </div>

                @endforeach

                </div>

            </div>


            {{-- PAGINACIÓN PRODUCTOS --}}
            @if($products->hasPages())

                <div class="mt-12">
                    {{ $products->links() }}
                </div>

            @endif

        @endif

    </div>

</section>



{{-- ========================================================= --}}
{{-- TIENDAS --}}
{{-- ========================================================= --}}
<section
    id="tiendas"
    class="vf-store-discovery relative overflow-hidden py-20 {{ $stores->count() === 1 ? 'vf-store-discovery--compact' : '' }}"
>

    <div class="vf-container relative">

        <div
            class="vf-stores-heading grid gap-8 lg:grid-cols-[.7fr_1.3fr] lg:items-end"
        >

            <div>

                <div
                    class="inline-flex rounded-full bg-[#ffb8c8] px-4 py-2 text-xs font-black uppercase tracking-[.14em] text-[#713c50]"
                >
                    Lugares con onda
                </div>


                @if($search)

                    <h2 class="vf-section-title mt-5">
                        Tiendas relacionadas con

                        <span class="text-[#ff6b57]">
                            “{{ $search }}”
                        </span>
                    </h2>

                @else

                    <h2 class="vf-section-title mt-5">
                        Tiendas que vale la pena

                        <span class="text-[#ff6b57]">
                            conocer
                        </span>
                    </h2>

                @endif

            </div>


            <p
                class="max-w-xl text-lg leading-8 text-[#78666a] lg:justify-self-end"
            >
                Detrás de cada tienda hay personas, ideas y productos
                esperando llegar a alguien que los disfrute.
            </p>

        </div>


        @if($stores->isEmpty())

            <div
                class="mt-12 rounded-[34px] border-2 border-dashed border-[#d4bcaa] bg-[#fffaf3] px-6 py-16 text-center"
            >

                <div
                    class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-white text-[#ff6b57] shadow-sm"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.8"
                        stroke="currentColor"
                        class="h-7 w-7"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m21 21-4.5-4.5m2.25-5.25a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z"
                        />
                    </svg>

                </div>


                <h3 class="mt-5 text-2xl font-black">
                    Mmm... no encontramos eso.
                </h3>


                <p class="mt-2 text-[#826f71]">
                    Prueba buscando con otra palabra.
                </p>


                <a
                    href="{{ route('home') }}"
                    class="mt-6 inline-flex rounded-full bg-[#ff6b57] px-6 py-3 font-black text-white"
                >
                    Volver a explorar
                </a>

            </div>


        @else

            <div
                class="vf-store-grid mt-12 grid gap-7"
            >

                @foreach($stores as $store)

                    <a
                        href="{{ route('stores.show', $store) }}"
                        class="vf-card vf-store-card group overflow-hidden bg-white"
                    >

                        @if(!empty($store->gallery_images))
                            <div class="vf-store-card__cover">
                                <img src="{{ asset('storage/' . $store->gallery_images[0]) }}" alt="" loading="lazy" class="h-full w-full object-cover">
                            </div>
                        @endif

                        <div class="vf-store-card__body">
                            @if($store->isFeatured() || $store->isVerified())
                                <div class="vf-store-card__badges">
                                    @if($store->isFeatured())
                                        <span class="vf-store-card__featured">Tienda destacada</span>
                                    @endif
                                    @if($store->isVerified())
                                        <span class="vf-store-card__verified"><i data-lucide="badge-check" aria-hidden="true"></i> Verificada</span>
                                    @endif
                                </div>
                            @endif

                            <div class="vf-store-card__identity">
                                @if($store->logo)
                                    <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" loading="lazy" class="vf-store-logo bg-white object-contain">
                                @else
                                    <span class="vf-store-logo vf-store-logo--initial" aria-hidden="true">{{ mb_strtoupper(mb_substr($store->name, 0, 1)) }}</span>
                                @endif
                                <div>
                                    <h3>{{ $store->name }}</h3>
                                    <p class="vf-store-card__city"><i data-lucide="map-pin" aria-hidden="true"></i>{{ $store->city ?: 'Perú' }}</p>
                                </div>
                            </div>

                            <p class="vf-store-card__description">{{ $store->description ?: 'Descubre lo que este negocio tiene para ti.' }}</p>

                            <div class="vf-store-card__details">
                                <span>{{ $store->delivery_available ? 'Con delivery' : 'Sin delivery' }}</span>
                                @if($store->address)
                                    <span class="vf-store-card__address">{{ $store->address }}</span>
                                @endif
                            </div>

                            <div class="vf-store-card__footer">
                                <span><strong>{{ $store->products_count }}</strong> {{ $store->products_count == 1 ? 'producto' : 'productos' }}</span>
                                <span class="vf-store-card__visit">Ver tienda <i data-lucide="arrow-right" aria-hidden="true"></i></span>
                            </div>
                        </div>

                    </a>

                @endforeach

            </div>

        @endif

    </div>

</section>



{{-- ========================================================= --}}
{{-- CÓMO FUNCIONA --}}
{{-- ========================================================= --}}
<section
    id="como-funciona"
    class="vf-how-it-works relative overflow-hidden py-20"
>

    <div class="vf-container">

        <div class="mx-auto max-w-[700px] text-center">

            <div
                class="inline-flex rounded-full bg-[#ffd66b] px-4 py-2 text-xs font-black uppercase tracking-[.13em] text-[#624f24]"
            >
                Cero complicaciones
            </div>


            <h2 class="vf-section-title mt-5">
                Comprar aquí se siente

                <span class="text-[#ff6b57]">
                    fácil de verdad
                </span>
            </h2>

        </div>


        <div class="vf-steps mt-14 grid gap-5 md:grid-cols-3">

            {{-- PASO 1 --}}
            <div
                class="vf-step vf-step--discover p-8"
            >

                <div
                    class="flex h-14 w-14 items-center justify-center rounded-[20px] bg-white text-[#7a622a] shadow-sm"
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


                <div
                    class="mt-8 text-sm font-black uppercase tracking-[.16em] text-[#7a622a]"
                >
                    Paso uno
                </div>


                <h3
                    class="mt-2 text-3xl font-black tracking-[-.04em]"
                >
                    Descubre
                </h3>


                <p class="mt-3 leading-7 text-[#665526]">
                    Mira tiendas, descubre productos y encuentra algo
                    que realmente te guste.
                </p>

            </div>


            {{-- PASO 2 --}}
            <div
                class="vf-step vf-step--choose p-8"
            >

                <div
                    class="flex h-14 w-14 items-center justify-center rounded-[20px] bg-white text-[#81475a] shadow-sm"
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


                <div
                    class="mt-8 text-sm font-black uppercase tracking-[.16em] text-[#81475a]"
                >
                    Paso dos
                </div>


                <h3
                    class="mt-2 text-3xl font-black tracking-[-.04em]"
                >
                    Elige
                </h3>


                <p class="mt-3 leading-7 text-[#704252]">
                    Revisa el producto, su precio y visita el catálogo
                    completo del negocio.
                </p>

            </div>


            {{-- PASO 3 --}}
            <div
                class="vf-step vf-step--contact p-8"
            >

                <div
                    class="flex h-14 w-14 items-center justify-center rounded-[20px] bg-white text-[#356756] shadow-sm"
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
                            d="M7.5 8.25h9m-9 3h6m-8.25 8.25 1.5-4.5a8.25 8.25 0 1 1 3 3l-4.5 1.5Z"
                        />
                    </svg>

                </div>


                <div
                    class="mt-8 text-sm font-black uppercase tracking-[.16em] text-[#356756]"
                >
                    Paso tres
                </div>


                <h3
                    class="mt-2 text-3xl font-black tracking-[-.04em]"
                >
                    Escríbele
                </h3>


                <p class="mt-3 leading-7 text-[#365e52]">
                    Abre WhatsApp y habla directamente con la tienda.
                    Sin formularios eternos.
                </p>

            </div>

        </div>

    </div>

</section>



{{-- ========================================================= --}}
{{-- CTA --}}
{{-- ========================================================= --}}
@guest

<section class="vf-seller-invitation">

    <div
        class="vf-container relative py-14"
    >

        <div
            class="relative flex flex-col gap-9 lg:flex-row lg:items-center lg:justify-between"
        >

            <div class="max-w-[700px]">

                <div
                    class="inline-flex rounded-full bg-white/20 px-4 py-2 text-sm font-black text-white backdrop-blur"
                >
                    Tu negocio también merece estar aquí
                </div>


                <h2
                    class="mt-5 text-[42px] font-black leading-[1] tracking-[-.05em] text-white sm:text-[56px]"
                >
                    ¿Vendemos algo increíble juntos?
                </h2>


                <p
                    class="mt-5 max-w-xl text-lg leading-8 text-[#fff2ee]"
                >
                    Crea tu espacio, sube tus productos y deja que
                    nuevas personas descubran tu negocio.
                </p>

            </div>


            <a
                href="{{ route('register') }}"
                class="inline-flex flex-shrink-0 items-center justify-center rounded-full bg-[#ffd66b] px-8 py-5 text-lg font-black text-[#47341e] shadow-xl transition hover:scale-[1.04]"
            >
                Quiero mi tienda
            </a>

        </div>

    </div>

</section>

@endguest

@endsection
