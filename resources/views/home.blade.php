@extends('layouts.public')

@section('title', 'VendeFácil - Encuentra algo que te encante')

@section('description')
Descubre tiendas independientes, productos increíbles y compra directamente por WhatsApp.
@endsection


@section('content')

{{-- ========================================================= --}}
{{-- HERO --}}
{{-- ========================================================= --}}
<section class="relative overflow-hidden bg-[#fff3e5]">

    <div
        class="absolute -left-24 top-16 h-56 w-56 rounded-full bg-[#ffd66b] opacity-45"
    ></div>

    <div
        class="absolute -right-20 top-0 h-72 w-72 rounded-full bg-[#f3c3cf] opacity-75"
    ></div>


    <div
        class="vf-container relative grid min-h-[620px] items-center gap-12 py-16 lg:grid-cols-[1.08fr_.92fr] lg:py-20"
    >

        {{-- ================================================= --}}
        {{-- TEXTO --}}
        {{-- ================================================= --}}
        <div>

            <div
                class="inline-flex items-center gap-2 rounded-full bg-[#f3c95f] px-5 py-3 text-sm font-black text-[#4c382e] shadow-sm"
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor"
                    viewBox="0 0 24 24"
                    class="h-4 w-4 text-[#ff7b67]"
                >
                    <path
                        d="M12 2l1.6 4.4L18 8l-4.4 1.6L12 14l-1.6-4.4L6 8l4.4-1.6L12 2Z"
                    />

                    <path
                        d="M19 14l.9 2.1L22 17l-2.1.9L19 20l-.9-2.1L16 17l2.1-.9L19 14Z"
                    />
                </svg>

                Aquí siempre hay algo nuevo

            </div>


            <h1
                class="mt-7 max-w-[700px] text-[47px] font-black leading-[.98] tracking-[-.055em] text-[#35272a] sm:text-[64px] lg:text-[78px]"
            >
                Encuentra cosas

                <span class="relative inline-block text-[#ff604c]">
                    que te hagan
                </span>

                sonreír.
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
                    class="vf-search flex flex-col gap-2 rounded-[24px] bg-white p-2 sm:flex-row"
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
                    class="mt-3 grid gap-3 rounded-[22px] bg-white/70 p-3 backdrop-blur sm:grid-cols-[minmax(0,1fr)_auto] sm:items-end"
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
                            class="mb-1 block text-xs font-black uppercase tracking-[.12em] text-[#8b7678]"
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
                            class="flex h-14 w-full items-center justify-between gap-3 rounded-[16px] border border-[#eaded5] bg-[#fffdf9] px-4 text-left text-sm font-black text-[#4b3a3d] shadow-sm transition hover:border-[#ffd2c8] hover:bg-white focus:border-[#ff8b78] focus:outline-none focus:ring-2 focus:ring-[#ff8b78]/25"
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
                        class="flex h-14 cursor-pointer items-center justify-center gap-3 rounded-[16px] border border-[#eaded5] bg-[#fffdf9] px-5 transition hover:bg-[#fff8f0]"
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


            {{-- ================================================= --}}
            {{-- CATEGORÍAS FUNCIONALES --}}
            {{-- ================================================= --}}
            <div class="mt-7">

                <div class="flex flex-wrap gap-3">

                    {{-- TODOS --}}
                    <a
                        href="{{ route('home', array_filter([
                            'buscar' => $search ?: null,
                            'ciudad' => $city ?: null,
                            'delivery' => $delivery ? 1 : null,
                        ])) }}#productos"
                        class="
                            inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-bold transition

                            {{ !$selectedCategory
                                ? 'bg-[#442d35] text-white shadow-md'
                                : 'bg-white/75 text-[#684f53] hover:bg-white'
                            }}
                        "
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.8"
                            stroke="currentColor"
                            class="h-4 w-4"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4.5 6.75h15M4.5 12h15M4.5 17.25h15"
                            />
                        </svg>

                        Todos
                    </a>


                    @foreach($categories as $category)

                        <a
                            href="{{ route('home', array_filter([
                                'categoria' => $category->slug,
                                'buscar' => $search ?: null,
                                'ciudad' => $city ?: null,
                                'delivery' => $delivery ? 1 : null,
                            ])) }}#productos"
                            class="
                                inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-bold transition

                                {{ $selectedCategory?->id === $category->id
                                    ? 'bg-[#ff6b57] text-white shadow-md'
                                    : 'bg-white/75 text-[#684f53] hover:bg-white'
                                }}
                            "
                        >

                            {{-- ICONO SEGÚN CATEGORÍA --}}
                            @switch($category->slug)

                                @case('comida')

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                        class="h-4 w-4"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M6 3.75v6m3-6v6M7.5 9.75v10.5M15.75 3.75c2.25 2.25 2.25 6.75 0 9v7.5"
                                        />
                                    </svg>

                                    @break


                                @case('moda')

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                        class="h-4 w-4"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M8.25 4.5 12 6.75l3.75-2.25 4.5 4.5-3 2.25V20.25H6.75V11.25l-3-2.25 4.5-4.5Z"
                                        />
                                    </svg>

                                    @break


                                @case('tecnologia')

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                        class="h-4 w-4"
                                    >
                                        <rect
                                            x="4"
                                            y="4"
                                            width="16"
                                            height="12"
                                            rx="2"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            d="M8 20h8M12 16v4"
                                        />
                                    </svg>

                                    @break


                                @case('belleza')

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                        class="h-4 w-4"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M9 5.25A2.25 2.25 0 0 1 11.25 3h1.5A2.25 2.25 0 0 1 15 5.25V6h1.5A1.5 1.5 0 0 1 18 7.5v10.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 6 18V7.5A1.5 1.5 0 0 1 7.5 6H9v-.75Z"
                                        />
                                    </svg>

                                    @break


                                @case('hogar')

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                        class="h-4 w-4"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M3.75 10.5 12 4.5l8.25 6V19.5H15v-5.25H9V19.5H3.75v-9Z"
                                        />
                                    </svg>

                                    @break


                                @case('regalos')

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                        class="h-4 w-4"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M3.75 9h16.5v11.25H3.75V9Zm8.25 0v11.25M2.25 6.75h19.5V9H2.25V6.75ZM12 6.75c-1.5-3-4.5-3-4.5-1.125S9.75 6.75 12 6.75Zm0 0c1.5-3 4.5-3 4.5-1.125S14.25 6.75 12 6.75Z"
                                        />
                                    </svg>

                                    @break


                                @case('servicios')

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                        class="h-4 w-4"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M14.25 6.75a4.5 4.5 0 0 0-5.91 5.91L3.75 17.25l3 3 4.59-4.59a4.5 4.5 0 0 0 5.91-5.91l-2.5 2.5-3-3 2.5-2.5Z"
                                        />
                                    </svg>

                                    @break


                                @default

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                        class="h-4 w-4"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M4.5 5.25h6v6h-6v-6Zm9 0h6v6h-6v-6Zm-9 9h6v6h-6v-6Zm9 0h6v6h-6v-6Z"
                                        />
                                    </svg>

                            @endswitch


                            {{ $category->name }}


                            @if($category->products_count > 0)

                                <span
                                    class="
                                        rounded-full px-2 py-0.5 text-[10px] font-black

                                        {{ $selectedCategory?->id === $category->id
                                            ? 'bg-white/20 text-white'
                                            : 'bg-[#fff0db] text-[#a36c53]'
                                        }}
                                    "
                                >
                                    {{ $category->products_count }}
                                </span>

                            @endif

                        </a>

                    @endforeach

                </div>


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
    class="relative overflow-hidden py-20"
>

    <div
        class="absolute -left-24 top-20 h-56 w-56 rounded-full bg-[#ffb8c8]/30"
    ></div>


    <div class="vf-container relative">

        <div
            class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between"
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

            <div
                class="mt-11 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4"
            >

                @foreach($products as $product)

                    <a
                        href="{{ route('stores.show', $product->store) }}"
                        class="vf-card vf-product-image group overflow-hidden rounded-[28px] border border-[#eaded5] bg-[#fffdf9]"
                    >

                        {{-- IMAGEN --}}
                        <div
                            class="relative h-[270px] overflow-hidden"
                        >

                            @if($product->image)

                                <img
                                    src="{{ asset('storage/' . $product->image) }}"
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
                                class="absolute left-4 top-4 max-w-[calc(100%-2rem)] truncate rounded-full bg-white/90 px-3 py-2 text-xs font-black text-[#5f474b] shadow-md backdrop-blur"
                            >
                                {{ $product->store->name }}
                            </span>


                            {{-- CATEGORÍA --}}
                            @if($product->category)

                                <span
                                    class="absolute bottom-4 left-4 rounded-full bg-[#442d35]/90 px-3 py-1.5 text-[11px] font-black text-white backdrop-blur"
                                >
                                    {{ $product->category->name }}
                                </span>

                            @endif

                        </div>


                        {{-- INFORMACIÓN --}}
                        <div class="p-5">

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

                @endforeach

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
    class="relative overflow-hidden bg-[#f4eadf] py-20"
>

    <div
        class="vf-doodle -right-16 top-24 h-64 w-64 vf-blob bg-[#ffd66b]/50"
    ></div>


    <div class="vf-container relative">

        <div
            class="grid gap-8 lg:grid-cols-[.7fr_1.3fr] lg:items-end"
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
                class="mt-12 grid gap-7 md:grid-cols-2 lg:grid-cols-3"
            >

                @foreach($stores as $store)

                    @php
                        $coverClass = match($loop->index % 4) {
                            0 => 'vf-shop-cover-1',
                            1 => 'vf-shop-cover-2',
                            2 => 'vf-shop-cover-3',
                            default => 'vf-shop-cover-4',
                        };
                    @endphp


                    <a
                        href="{{ route('stores.show', $store) }}"
                        class="vf-card group overflow-hidden rounded-[32px] bg-[#fffdf9]"
                    >

                        {{-- PORTADA --}}
                        <div
                            class="relative h-[145px] {{ $coverClass }}"
                        >

                            <div
                                class="absolute right-5 top-5 rounded-full bg-white/25 px-3 py-1.5 text-xs font-black text-white backdrop-blur-md"
                            >
                                Abierto
                            </div>

                        </div>


                        {{-- INFO --}}
                        <div class="relative px-6 pb-6">

                            <div class="-mt-11">

                                @if($store->logo)

                                    <img
                                        src="{{ asset('storage/' . $store->logo) }}"
                                        alt="{{ $store->name }}"
                                        class="h-[88px] w-[88px] rounded-[25px] border-[5px] border-[#fffdf9] bg-white object-cover shadow-lg"
                                    >

                                @else

                                    <div
                                        class="flex h-[88px] w-[88px] items-center justify-center rounded-[25px] border-[5px] border-[#fffdf9] bg-[#ffd66b] text-3xl font-black shadow-lg"
                                    >
                                        {{ strtoupper(substr($store->name, 0, 1)) }}
                                    </div>

                                @endif

                            </div>


                            <div class="mt-4">

                                <h3
                                    class="text-[23px] font-black tracking-[-.035em] text-[#38292c]"
                                >
                                    {{ $store->name }}
                                </h3>


                                <div class="mt-3 flex flex-wrap gap-2">

                                    <span
                                        class="inline-flex max-w-full items-center gap-2 rounded-full bg-[#fff0db] px-3 py-1.5 text-[11px] font-black text-[#a36c53]"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.8"
                                            stroke="currentColor"
                                            class="h-3.5 w-3.5 flex-shrink-0 text-[#f26452]"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M12 21s6.75-4.2 6.75-10.5a6.75 6.75 0 1 0-13.5 0C5.25 16.8 12 21 12 21Z"
                                            />
                                            <circle cx="12" cy="10.5" r="2"/>
                                        </svg>

                                        <span class="truncate">
                                            {{ $store->city ? $store->city . ', Perú' : 'Perú' }}
                                        </span>
                                    </span>


                                    <span
                                        class="inline-flex rounded-full px-3 py-1.5 text-[11px] font-black {{ $store->delivery_available ? 'bg-[#e7f5ef] text-[#427764]' : 'bg-[#eee5df] text-[#8b7678]' }}"
                                    >
                                        {{ $store->delivery_available ? 'Hace delivery' : 'Sin delivery' }}
                                    </span>

                                </div>

                                @if($store->address)
                                    <p class="mt-2 truncate text-xs font-bold text-[#9a8185]">
                                        {{ $store->address }}
                                    </p>
                                @endif


                                <p
                                    class="mt-4 line-clamp-2 min-h-[48px] text-sm leading-6 text-[#796669]"
                                >
                                    {{ $store->description ?: 'Una tienda con cosas que podrían gustarte mucho.' }}
                                </p>


                                <div
                                    class="mt-6 flex items-center justify-between border-t border-[#eee2d9] pt-5"
                                >

                                    <div>

                                        <div
                                            class="text-xs font-bold uppercase tracking-[.1em] text-[#ae9295]"
                                        >
                                            Catálogo
                                        </div>

                                        <div
                                            class="mt-1 font-black text-[#48373a]"
                                        >
                                            {{ $store->products_count }}

                                            {{ $store->products_count == 1
                                                ? 'producto'
                                                : 'productos'
                                            }}
                                        </div>

                                    </div>


                                    <div
                                        class="rounded-full bg-[#3e2030] px-4 py-2 text-sm font-black text-white transition group-hover:bg-[#ff6b57]"
                                    >
                                        Entrar →
                                    </div>

                                </div>

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
    class="relative overflow-hidden bg-[#fffaf2] py-20"
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


        <div class="mt-14 grid gap-5 md:grid-cols-3">

            {{-- PASO 1 --}}
            <div
                class="vf-card rounded-[34px] bg-[#ffd66b] p-8"
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
                class="vf-card rounded-[34px] bg-[#ffb8c8] p-8"
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
                class="vf-card rounded-[34px] bg-[#98dbc6] p-8"
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

<section class="px-3 pb-20 sm:px-6">

    <div
        class="vf-container relative overflow-hidden rounded-[42px] bg-[#ff6b57] px-6 py-14 sm:px-12 lg:px-16"
    >

        <div
            class="absolute -right-10 -top-10 h-44 w-44 rounded-full bg-[#ffd66b]"
        ></div>


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
