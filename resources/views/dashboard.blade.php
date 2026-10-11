<x-app-layout>

    @php
        $store = auth()->user()->stores()->first();

        $recentProducts = $store
            ? $store->products()->latest()->take(4)->get()
            : collect();

        $totalProducts = $store
            ? $store->products()->count()
            : 0;

        $activeProducts = $store
            ? $store->products()->where('active', true)->count()
            : 0;

        $inactiveProducts = max($totalProducts - $activeProducts, 0);

        $whatsappClicks = $store
            ? $store->whatsappClicks()->count()
            : 0;

        $todayWhatsappClicks = $store
            ? $store->whatsappClicks()->whereDate('created_at', today())->count()
            : 0;

        $storeVisits = $store
            ? $store->visits()->count()
            : 0;

        $todayStoreVisits = $store
            ? $store->visits()->whereDate('created_at', today())->count()
            : 0;

        $conversionRate = $storeVisits > 0
            ? round(($whatsappClicks / $storeVisits) * 100)
            : 0;

        $todayConversionRate = $todayStoreVisits > 0
            ? round(($todayWhatsappClicks / $todayStoreVisits) * 100)
            : 0;

        $topProducts = $store
            ? $store->products()
                ->withCount('whatsappClicks')
                ->orderByDesc('whatsapp_clicks_count')
                ->latest()
                ->take(5)
                ->get()
            : collect();

        $setupItems = $store
            ? collect([
                [
                    'label' => 'Logo de la tienda',
                    'done' => filled($store->logo),
                    'hint' => 'Ayuda a que tu negocio se reconozca rápido.',
                ],
                [
                    'label' => 'Descripción clara',
                    'done' => filled($store->description),
                    'hint' => 'Cuenta qué vendes y por qué elegirte.',
                ],
                [
                    'label' => 'Ciudad y dirección',
                    'done' => filled($store->city) && filled($store->address),
                    'hint' => 'Tus clientes sabrán dónde estás en Perú.',
                ],
                [
                    'label' => 'Delivery configurado',
                    'done' => ! is_null($store->delivery_available),
                    'hint' => 'Aclara si puedes entregar pedidos.',
                ],
                [
                    'label' => 'Primer producto publicado',
                    'done' => $totalProducts > 0,
                    'hint' => 'Tu catálogo necesita al menos un producto.',
                ],
            ])
            : collect();

        $setupCompleted = $setupItems->where('done', true)->count();

        $setupPercent = $setupItems->isNotEmpty()
            ? round(($setupCompleted / $setupItems->count()) * 100)
            : 0;

        $productLimit = config('vendefacil.free_product_limit');
        $remainingProductSlots = $store
            ? $store->remainingFreeProducts($totalProducts)
            : 0;
        $planPrice = config('vendefacil.unlimited_products_price');
    @endphp


    {{-- ========================================================= --}}
    {{-- FONDO GENERAL --}}
    {{-- ========================================================= --}}

    <div class="min-h-screen bg-[#fffaf5]">

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">


            {{-- ================================================= --}}
            {{-- CABECERA --}}
            {{-- ================================================= --}}

            <div
                class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between"
            >

                <div>

                    <div
                        class="inline-flex items-center gap-2 rounded-full bg-[#ffe3a3] px-4 py-2 text-xs font-black uppercase tracking-[.12em] text-[#72581f]"
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
                                d="M4.5 19.5h15v-9.75L12 4.5 4.5 9.75V19.5Z"
                            />
                        </svg>

                        Panel del vendedor
                    </div>


                    <h1
                        class="mt-4 text-3xl font-black tracking-[-.04em] text-[#35272a] sm:text-4xl"
                    >
                        Hola, {{ auth()->user()->name }}
                    </h1>


                    <p
                        class="mt-2 text-base text-[#806d70]"
                    >
                        Administra tu tienda y mantén tu catálogo actualizado.
                    </p>

                </div>


                @if($store && $store->active)

                    <a
                        href="{{ route('stores.show', $store) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center justify-center gap-2 rounded-full bg-[#442d35] px-5 py-3 text-sm font-black text-white transition hover:bg-[#ff6b57]"
                    >
                        Ver tienda pública

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
                                d="M13.5 4.5H19.5V10.5M19.5 4.5 10.5 13.5M19.5 13.5v4.5A1.5 1.5 0 0 1 18 19.5H6A1.5 1.5 0 0 1 4.5 18V6A1.5 1.5 0 0 1 6 4.5h4.5"
                            />
                        </svg>
                    </a>

                @elseif($store)

                    <span
                        class="inline-flex cursor-not-allowed items-center justify-center gap-2 rounded-full bg-[#eee5df] px-5 py-3 text-sm font-black text-[#9a8583]"
                    >
                        Tienda pausada
                    </span>

                @endif

            </div>


            {{-- ================================================= --}}
            {{-- MENSAJES --}}
            {{-- ================================================= --}}

            @if(session('success'))

                <div
                    class="mt-6 flex items-start gap-3 rounded-[18px] border border-[#bfe5d8] bg-[#edf8f4] px-4 py-3 text-sm font-semibold text-[#3d7764]"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        stroke="currentColor"
                        class="mt-0.5 h-5 w-5 flex-shrink-0"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m5 12 4 4L19 6"
                        />
                    </svg>

                    {{ session('success') }}

                </div>

            @endif


            @if(session('error'))

                <div
                    class="mt-6 flex items-start gap-3 rounded-[18px] border border-[#f3c5bc] bg-[#fff0ec] px-4 py-3 text-sm font-semibold text-[#a24536]"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        stroke="currentColor"
                        class="mt-0.5 h-5 w-5 flex-shrink-0"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 9v3.75m0 3h.008v.008H12v-.008Z"
                        />
                    </svg>

                    {{ session('error') }}

                </div>

            @endif


            @if($store)

                <div
                    class="mt-6 rounded-[24px] border border-[#eaded5] bg-white p-5"
                >
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[.12em] text-[#9b8583]">
                                Plan de productos
                            </p>

                            <h2 class="mt-2 text-xl font-black tracking-[-.03em] text-[#35272a]">
                                @if($store->unlimited_products)
                                    Productos ilimitados activos
                                @else
                                    {{ $totalProducts }} de {{ $productLimit }} productos gratis usados
                                @endif
                            </h2>

                            <p class="mt-1 text-sm leading-6 text-[#806d70]">
                                @if($store->unlimited_products)
                                    Puedes publicar productos sin límite.
                                @else
                                    Te quedan {{ $remainingProductSlots }} productos gratis. El plan ilimitado cuesta S/ {{ $planPrice }}.
                                @endif
                            </p>

                            @if(! $store->unlimited_products && $remainingProductSlots <= 2)
                                <div class="mt-3 rounded-[18px] bg-[#fff3da] px-4 py-3 text-sm font-black text-[#806226]">
                                    Estás cerca del límite gratis. Puedes activar ilimitado desde Mi plan cuando quieras.
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <a
                                href="{{ route('products.index') }}"
                                class="inline-flex items-center justify-center rounded-full bg-[#442d35] px-5 py-3 text-sm font-black text-white transition hover:bg-[#ff6b57]"
                            >
                                Gestionar catálogo
                            </a>

                            @unless($store->unlimited_products)
                                <a
                                    href="{{ route('seller.plan.index') }}"
                                    class="inline-flex items-center justify-center rounded-full bg-[#fff3da] px-5 py-3 text-sm font-black text-[#806226] transition hover:bg-[#ffe9ba]"
                                >
                                    Desbloquear ilimitado
                                </a>
                            @endunless
                        </div>
                    </div>
                </div>

            @endif


            {{-- ================================================= --}}
            {{-- SIN TIENDA --}}
            {{-- ================================================= --}}

            @if(!$store)

                <div
                    class="mt-8 overflow-hidden rounded-[30px] border border-[#eaded5] bg-white shadow-[0_18px_45px_rgba(91,53,43,.08)]"
                >

                    <div
                        class="h-[6px] bg-gradient-to-r from-[#ff7f6b] via-[#ffae78] to-[#ffd66b]"
                    ></div>


                    <div
                        class="px-6 py-14 text-center"
                    >

                        <div
                            class="mx-auto flex h-16 w-16 items-center justify-center rounded-[22px] bg-[#fff0e4] text-[#ff6b57]"
                        >

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.8"
                                stroke="currentColor"
                                class="h-8 w-8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3.75 9.75 12 4.5l8.25 5.25M5.25 8.8V19.5h13.5V8.8M9 19.5v-5.25h6v5.25"
                                />
                            </svg>

                        </div>


                        <h2
                            class="mt-5 text-2xl font-black text-[#35272a]"
                        >
                            Todavía no tienes una tienda
                        </h2>


                        <p
                            class="mx-auto mt-3 max-w-xl leading-7 text-[#806d70]"
                        >
                            Crea tu espacio, publica tus productos y permite
                            que tus clientes te contacten directamente por WhatsApp.
                        </p>


                        <a
                            href="{{ route('stores.create') }}"
                            class="mt-6 inline-flex items-center gap-2 rounded-full bg-[#ff6b57] px-6 py-3.5 font-black text-white transition hover:bg-[#ec5542]"
                        >
                            Crear mi tienda

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
                                    d="M12 4.5v15M4.5 12h15"
                                />
                            </svg>
                        </a>

                    </div>

                </div>


            @else


                {{-- ================================================= --}}
                {{-- RESUMEN PRINCIPAL --}}
                {{-- ================================================= --}}

                <div
                    class="mt-8 grid gap-6 lg:grid-cols-[1.65fr_.85fr]"
                >

                    {{-- TIENDA --}}
                    <div
                        class="overflow-hidden rounded-[28px] border border-[#eaded5] bg-white shadow-[0_16px_40px_rgba(91,53,43,.07)]"
                    >

                        <div
                            class="h-[6px] bg-gradient-to-r from-[#ff7f6b] via-[#ffad78] to-[#ffd66b]"
                        ></div>


                        <div class="p-6 sm:p-7">

                            <div
                                class="flex flex-col gap-6 sm:flex-row sm:items-center"
                            >

                                {{-- LOGO --}}
                                <div class="flex-shrink-0">

                                    @if($store->logo)

                                        <img
                                            src="{{ asset('storage/' . $store->logo) }}"
                                            alt="{{ $store->name }}"
                                            class="h-24 w-24 rounded-[24px] border border-[#eaded5] object-cover shadow-sm"
                                        >

                                    @else

                                        <div
                                            class="flex h-24 w-24 items-center justify-center rounded-[24px] bg-[#ffd66b] text-3xl font-black text-[#5e4723]"
                                        >
                                            {{ strtoupper(substr($store->name, 0, 1)) }}
                                        </div>

                                    @endif

                                </div>


                                {{-- INFO --}}
                                <div class="min-w-0 flex-1">

                                    <div
                                        class="flex flex-wrap items-center gap-3"
                                    >

                                        <h2
                                            class="text-2xl font-black tracking-[-.035em] text-[#35272a]"
                                        >
                                            {{ $store->name }}
                                        </h2>


                                        @if($store->active)

                                            <span
                                                class="inline-flex items-center gap-2 rounded-full bg-[#e7f5ef] px-3 py-1.5 text-xs font-black text-[#427764]"
                                            >
                                                <span
                                                    class="h-2 w-2 rounded-full bg-[#69b59e]"
                                                ></span>

                                                Activa
                                            </span>

                                        @else

                                            <span
                                                class="rounded-full bg-[#fff0ec] px-3 py-1.5 text-xs font-black text-[#b45040]"
                                            >
                                                Inactiva
                                            </span>

                                        @endif

                                    </div>


                                    <p
                                        class="mt-3 max-w-2xl text-sm leading-6 text-[#806d70]"
                                    >
                                        {{ $store->description ?: 'Tu tienda todavía no tiene una descripción.' }}
                                    </p>


                                    <div
                                        class="mt-5 flex flex-wrap gap-x-6 gap-y-3 text-sm"
                                    >

                                        <div
                                            class="flex items-center gap-2 text-[#78666a]"
                                        >

                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.8"
                                                stroke="currentColor"
                                                class="h-4 w-4 text-[#ff6b57]"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M12 21s6.75-4.2 6.75-10.5a6.75 6.75 0 1 0-13.5 0C5.25 16.8 12 21 12 21Z"
                                                />
                                                <circle cx="12" cy="10.5" r="2"/>
                                            </svg>

                                            {{ $store->city ? $store->city . ', Perú' : 'Sin ciudad' }}
                                            @if($store->address)
                                                · {{ $store->address }}
                                            @endif

                                        </div>


                                        <div
                                            class="flex items-center gap-2 text-[#78666a]"
                                        >

                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.8"
                                                stroke="currentColor"
                                                class="h-4 w-4 text-[#4e8b79]"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M7.5 8.25h9m-9 3h6m-8.25 8.25 1.5-4.5a8.25 8.25 0 1 1 3 3l-4.5 1.5Z"
                                                />
                                            </svg>

                                            {{ $store->whatsapp }}

                                        </div>


                                        <div
                                            class="flex items-center gap-2 text-[#78666a]"
                                        >
                                            <span
                                                class="inline-flex rounded-full px-3 py-1.5 text-xs font-black {{ $store->delivery_available ? 'bg-[#e7f5ef] text-[#427764]' : 'bg-[#eee5df] text-[#8b7678]' }}"
                                            >
                                                {{ $store->delivery_available ? 'Hace delivery' : 'Sin delivery' }}
                                            </span>
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- ACCIONES --}}
                    <div
                        class="rounded-[28px] border border-[#eaded5] bg-white p-6 shadow-[0_16px_40px_rgba(91,53,43,.07)]"
                    >

                        <div
                            class="flex items-center justify-between"
                        >

                            <div>

                                <p
                                    class="text-xs font-black uppercase tracking-[.12em] text-[#b38f3b]"
                                >
                                    Accesos
                                </p>

                                <h3
                                    class="mt-1 text-xl font-black text-[#35272a]"
                                >
                                    Acciones rápidas
                                </h3>

                            </div>


                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#fff0e4] text-[#ff6b57]"
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
                                        d="M12 4.5v15M4.5 12h15"
                                    />
                                </svg>
                            </div>

                        </div>


                        <div class="mt-5 space-y-2.5">

                            <a
                                href="{{ route('products.create') }}"
                                class="flex items-center justify-between rounded-[15px] bg-[#ff6b57] px-4 py-3 text-sm font-black text-white transition hover:bg-[#ed5542]"
                            >
                                Agregar producto

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
                                        d="M12 4.5v15M4.5 12h15"
                                    />
                                </svg>
                            </a>


                            <a
                                href="{{ route('products.index') }}"
                                class="flex items-center justify-between rounded-[15px] bg-[#fff5e8] px-4 py-3 text-sm font-black text-[#65523c] transition hover:bg-[#ffedcf]"
                            >
                                Ver productos

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


                            @if($store->active)

                                <a
                                    href="{{ route('stores.show', $store) }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="flex items-center justify-between rounded-[15px] bg-[#e8f5f0] px-4 py-3 text-sm font-black text-[#417563] transition hover:bg-[#dcefe8]"
                                >
                                    Ver tienda pública

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

                            @else

                                <span
                                    class="flex cursor-not-allowed items-center justify-between rounded-[15px] bg-[#eee5df] px-4 py-3 text-sm font-black text-[#9a8583]"
                                >
                                    Tienda pública pausada
                                </span>

                            @endif


                            <a
                                href="{{ route('stores.edit') }}"
                                class="flex items-center justify-between rounded-[15px] border border-[#eaded5] bg-[#fffdf9] px-4 py-3 text-sm font-black text-[#6f5b5e] transition hover:bg-[#f8eee7]"
                            >
                                Editar tienda

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
                                        d="m16.862 4.487 2.651 2.651M18.75 3.75a1.875 1.875 0 0 1 2.651 2.651L8.25 19.552 3.75 20.25l.698-4.5L17.599 2.599"
                                    />
                                </svg>
                            </a>


                            <form
                                action="{{ route('stores.toggle') }}"
                                method="POST"
                            >
                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="flex w-full items-center justify-between rounded-[15px] px-4 py-3 text-sm font-black transition {{ $store->active ? 'bg-[#fff0ec] text-[#b65040] hover:bg-[#ffe3dc]' : 'bg-[#e7f5ef] text-[#407460] hover:bg-[#d9eee5]' }}"
                                >
                                    {{ $store->active ? 'Pausar tienda' : 'Activar tienda' }}

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
                                </button>
                            </form>

                        </div>

                    </div>
                </div>


                {{-- ================================================= --}}
                {{-- RENDIMIENTO DE HOY --}}
                {{-- ================================================= --}}

                <div
                    class="mt-6 overflow-hidden rounded-[28px] border border-[#eaded5] bg-white shadow-[0_16px_40px_rgba(91,53,43,.06)]"
                >

                    <div
                        class="grid gap-5 p-6 lg:grid-cols-[1fr_2fr]"
                    >

                        <div>
                            <p
                                class="text-xs font-black uppercase tracking-[.12em] text-[#b38f3b]"
                            >
                                Rendimiento
                            </p>

                            <h3
                                class="mt-2 text-2xl font-black tracking-[-.035em] text-[#35272a]"
                            >
                                Movimiento de tu tienda
                            </h3>

                            <p
                                class="mt-2 text-sm leading-6 text-[#806d70]"
                            >
                                Una lectura rápida de visitas, contactos por WhatsApp y productos visibles.
                            </p>
                        </div>


                        <div
                            class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4"
                        >
                            <div class="rounded-[20px] border border-[#f0e3da] bg-[#fffaf4] p-4">
                                <p class="text-xs font-black uppercase tracking-[.12em] text-[#a48777]">
                                    Visitas hoy
                                </p>

                                <p class="mt-2 text-3xl font-black text-[#35272a]">
                                    {{ $todayStoreVisits }}
                                </p>
                            </div>

                            <div class="rounded-[20px] border border-[#d7eee6] bg-[#f0faf6] p-4">
                                <p class="text-xs font-black uppercase tracking-[.12em] text-[#6d9b8d]">
                                    WhatsApp hoy
                                </p>

                                <p class="mt-2 text-3xl font-black text-[#35272a]">
                                    {{ $todayWhatsappClicks }}
                                </p>
                            </div>

                            <div class="rounded-[20px] border border-[#ffe0d7] bg-[#fff6f3] p-4">
                                <p class="text-xs font-black uppercase tracking-[.12em] text-[#bf7668]">
                                    Conversión
                                </p>

                                <p class="mt-2 text-3xl font-black text-[#35272a]">
                                    {{ $todayConversionRate }}%
                                </p>

                                <p class="mt-1 text-xs font-bold text-[#9c8588]">
                                    {{ $conversionRate }}% total
                                </p>
                            </div>

                            <div class="rounded-[20px] border border-[#f0e3da] bg-[#fffdf9] p-4">
                                <p class="text-xs font-black uppercase tracking-[.12em] text-[#a48777]">
                                    Productos activos
                                </p>

                                <p class="mt-2 text-3xl font-black text-[#35272a]">
                                    {{ $activeProducts }}
                                </p>

                                @if($inactiveProducts > 0)
                                    <p class="mt-1 text-xs font-bold text-[#9c8588]">
                                        {{ $inactiveProducts }} ocultos
                                    </p>
                                @endif
                            </div>
                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- ESTADO DE TIENDA --}}
                {{-- ================================================= --}}

                <div
                    class="mt-6 overflow-hidden rounded-[28px] border border-[#eaded5] bg-white"
                >

                    <div
                        class="flex flex-col gap-5 p-6 lg:flex-row lg:items-center lg:justify-between"
                    >

                        <div class="max-w-2xl">

                            <div
                                class="inline-flex items-center gap-2 rounded-full bg-[#fff3da] px-3 py-1.5 text-xs font-black uppercase tracking-[.08em] text-[#806226]"
                            >
                                Preparación de tienda
                            </div>


                            <h3
                                class="mt-3 text-2xl font-black tracking-[-.035em] text-[#35272a]"
                            >
                                Tu tienda está al {{ $setupPercent }}%
                            </h3>


                            <p class="mt-2 text-sm leading-6 text-[#806d70]">
                                Completa estos puntos para que tu tienda se vea más confiable y fácil de comprar.
                            </p>

                        </div>


                        <div class="w-full lg:max-w-xs">

                            <div class="flex items-center justify-between text-sm font-black text-[#514043]">
                                <span>{{ $setupCompleted }} de {{ $setupItems->count() }} completos</span>
                                <span>{{ $setupPercent }}%</span>
                            </div>

                            <div class="mt-3 h-3 overflow-hidden rounded-full bg-[#f2e5da]">
                                <div
                                    class="h-full rounded-full bg-[#ff6b57]"
                                    style="width: {{ $setupPercent }}%"
                                ></div>
                            </div>

                        </div>

                    </div>


                    <div
                        class="grid gap-3 border-t border-[#f0e3da] bg-[#fffdf9] p-4 md:grid-cols-2 xl:grid-cols-5"
                    >

                        @foreach($setupItems as $item)

                            <div
                                class="rounded-[18px] border px-4 py-4 {{ $item['done'] ? 'border-[#c9e7dc] bg-[#f1faf6]' : 'border-[#eaded5] bg-white' }}"
                            >

                                <div class="flex items-start gap-3">

                                    <div
                                        class="mt-0.5 flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full {{ $item['done'] ? 'bg-[#69b59e] text-white' : 'bg-[#fff3da] text-[#806226]' }}"
                                    >
                                        @if($item['done'])
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
                                                    d="m5 12 4 4L19 6"
                                                />
                                            </svg>
                                        @else
                                            <span class="text-xs font-black">
                                                !
                                            </span>
                                        @endif
                                    </div>


                                    <div>
                                        <p class="text-sm font-black text-[#35272a]">
                                            {{ $item['label'] }}
                                        </p>

                                        <p class="mt-1 text-xs leading-5 text-[#806d70]">
                                            {{ $item['hint'] }}
                                        </p>
                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>


                    @if($setupPercent < 100)

                        <div
                            class="flex flex-col gap-3 border-t border-[#f0e3da] px-5 py-4 sm:flex-row sm:items-center sm:justify-between"
                        >
                            <p class="text-sm font-bold text-[#806d70]">
                                Completa lo pendiente para que tu tienda luzca mejor en el inicio.
                            </p>

                            <div class="flex flex-col gap-2 sm:flex-row">
                                <a
                                    href="{{ route('stores.edit') }}"
                                    class="inline-flex items-center justify-center rounded-[15px] bg-[#442d35] px-5 py-3 text-sm font-black text-white transition hover:bg-[#ff6b57]"
                                >
                                    Editar tienda
                                </a>

                                @if($totalProducts === 0)
                                    <a
                                        href="{{ route('products.create') }}"
                                        class="inline-flex items-center justify-center rounded-[15px] bg-[#ff6b57] px-5 py-3 text-sm font-black text-white transition hover:bg-[#ed5542]"
                                    >
                                        Agregar producto
                                    </a>
                                @endif
                            </div>
                        </div>

                    @endif

                </div>


                {{-- ================================================= --}}
                {{-- ESTADÍSTICAS --}}
                {{-- ================================================= --}}

                <div
                    class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3"
                >

                    {{-- PRODUCTOS --}}
                    <div
                        class="rounded-[24px] border border-[#eaded5] bg-white p-5 sm:col-span-2 lg:col-span-1"
                    >

                        <div
                            class="flex items-center justify-between"
                        >

                            <div>

                                <p
                                    class="text-sm font-bold text-[#8e797b]"
                                >
                                    Productos
                                </p>

                                <p
                                    class="mt-1 text-3xl font-black text-[#35272a]"
                                >
                                    {{ $totalProducts }}
                                </p>

                            </div>


                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#fff1d6] text-[#a07822]"
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

                        </div>

                    </div>


                    {{-- WHATSAPP --}}
                    <div
                        class="rounded-[24px] border border-[#eaded5] bg-white p-5"
                    >

                        <div
                            class="flex items-center justify-between"
                        >

                            <div>

                                <p
                                    class="text-sm font-bold text-[#8e797b]"
                                >
                                    Clics a WhatsApp
                                </p>

                                <p
                                    class="mt-1 text-3xl font-black text-[#35272a]"
                                >
                                    {{ $whatsappClicks }}
                                </p>

                                <p
                                    class="mt-1 text-xs font-bold text-[#9c8588]"
                                >
                                    {{ $todayWhatsappClicks }} hoy
                                </p>

                            </div>


                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#e7f5ef] text-[#4d8a77]"
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

                        </div>

                    </div>


                    {{-- VISITAS --}}
                    <div
                        class="rounded-[24px] border border-[#eaded5] bg-white p-5"
                    >

                        <div
                            class="flex items-center justify-between"
                        >

                            <div>

                                <p
                                    class="text-sm font-bold text-[#8e797b]"
                                >
                                    Visitas
                                </p>

                                <p
                                    class="mt-1 text-3xl font-black text-[#35272a]"
                                >
                                    {{ $storeVisits }}
                                </p>

                                <p
                                    class="mt-1 text-xs font-bold text-[#9c8588]"
                                >
                                    {{ $todayStoreVisits }} hoy
                                </p>

                            </div>


                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#ffe9e4] text-[#d65f4b]"
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
                                        d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"
                                    />
                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="3"
                                    />
                                </svg>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- PRODUCTOS MÁS CONSULTADOS --}}
                {{-- ================================================= --}}

                <div
                    class="mt-6 overflow-hidden rounded-[28px] border border-[#eaded5] bg-white"
                >

                    <div
                        class="flex flex-col gap-3 border-b border-[#f0e3da] px-6 py-5 sm:flex-row sm:items-center sm:justify-between"
                    >

                        <div>

                            <h3
                                class="text-xl font-black tracking-[-.025em] text-[#35272a]"
                            >
                                Productos más consultados
                            </h3>

                            <p
                                class="mt-1 text-sm text-[#8b7678]"
                            >
                                Los productos que más llevaron clientes hacia WhatsApp.
                            </p>

                        </div>

                    </div>


                    @if($topProducts->isEmpty() || $topProducts->sum('whatsapp_clicks_count') === 0)

                        <div class="px-6 py-10 text-center">

                            <div
                                class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#e7f5ef] text-[#4d8a77]"
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


                            <h4
                                class="mt-4 font-black text-[#35272a]"
                            >
                                Aún no hay consultas por producto
                            </h4>


                            <p
                                class="mt-2 text-sm text-[#806d70]"
                            >
                                Cuando alguien toque “Pedir por WhatsApp”, aquí verás qué productos generan más interés.
                            </p>

                        </div>


                    @else

                        <div class="divide-y divide-[#f0e3da]">

                            @foreach($topProducts as $product)

                                @if($product->whatsapp_clicks_count > 0)

                                    <div
                                        class="flex flex-col gap-4 px-6 py-4 sm:flex-row sm:items-center sm:justify-between"
                                    >

                                        <div class="flex min-w-0 items-center gap-4">

                                            @if($product->image)

                                                <img
                                                    src="{{ asset('storage/' . $product->image) }}"
                                                    alt="{{ $product->name }}"
                                                    class="h-14 w-14 flex-shrink-0 rounded-[16px] object-cover"
                                                >

                                            @else

                                                <div
                                                    class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-[16px] bg-[#f6e8de] text-[#c38f78]"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke-width="1.5"
                                                        stroke="currentColor"
                                                        class="h-7 w-7"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M3.75 6.75h16.5v12H3.75v-12Z"
                                                        />
                                                    </svg>
                                                </div>

                                            @endif


                                            <div class="min-w-0">

                                                <h4
                                                    class="truncate font-black text-[#35272a]"
                                                >
                                                    {{ $product->name }}
                                                </h4>

                                                <div
                                                    class="mt-1 flex flex-wrap gap-x-4 gap-y-1 text-sm font-bold text-[#8b7678]"
                                                >
                                                    <span>S/ {{ number_format($product->price, 2) }}</span>
                                                    <span>Stock {{ $product->stock }}</span>
                                                </div>

                                            </div>

                                        </div>


                                        <div
                                            class="inline-flex w-fit items-center gap-2 rounded-full bg-[#e7f5ef] px-4 py-2 text-sm font-black text-[#407460]"
                                        >
                                            {{ $product->whatsapp_clicks_count }}
                                            {{ $product->whatsapp_clicks_count === 1 ? 'consulta' : 'consultas' }}
                                        </div>

                                    </div>

                                @endif

                            @endforeach

                        </div>

                    @endif

                </div>


                {{-- ================================================= --}}
                {{-- PRODUCTOS RECIENTES --}}
                {{-- ================================================= --}}

                <div
                    class="mt-6 overflow-hidden rounded-[28px] border border-[#eaded5] bg-white"
                >

                    <div
                        class="flex flex-col gap-3 border-b border-[#f0e3da] px-6 py-5 sm:flex-row sm:items-center sm:justify-between"
                    >

                        <div>

                            <h3
                                class="text-xl font-black tracking-[-.025em] text-[#35272a]"
                            >
                                Productos recientes
                            </h3>

                            <p
                                class="mt-1 text-sm text-[#8b7678]"
                            >
                                Los últimos productos publicados en tu catálogo.
                            </p>

                        </div>


                        <a
                            href="{{ route('products.index') }}"
                            class="inline-flex items-center gap-2 text-sm font-black text-[#ff604c] transition hover:text-[#dc4e3d]"
                        >
                            Ver todos

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


                    @if($recentProducts->isEmpty())

                        <div class="px-6 py-12 text-center">

                            <div
                                class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#fff0e4] text-[#ff6b57]"
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


                            <h4
                                class="mt-4 font-black text-[#35272a]"
                            >
                                Todavía no tienes productos
                            </h4>


                            <p
                                class="mt-2 text-sm text-[#806d70]"
                            >
                                Agrega tu primer producto para comenzar a llenar tu catálogo.
                            </p>


                            <a
                                href="{{ route('products.create') }}"
                                class="mt-5 inline-flex rounded-full bg-[#ff6b57] px-5 py-3 text-sm font-black text-white transition hover:bg-[#ed5542]"
                            >
                                Agregar producto
                            </a>

                        </div>


                    @else

                        <div
                            class="grid grid-cols-1 gap-5 p-6 sm:grid-cols-2 lg:grid-cols-4"
                        >

                            @foreach($recentProducts as $product)

                                <div
                                    class="overflow-hidden rounded-[20px] border border-[#eaded5] bg-[#fffdf9] transition hover:-translate-y-1 hover:shadow-lg"
                                >

                                    @if($product->image)

                                        <img
                                            src="{{ asset('storage/' . $product->image) }}"
                                            alt="{{ $product->name }}"
                                            class="h-40 w-full object-cover"
                                        >

                                    @else

                                        <div
                                            class="flex h-40 items-center justify-center bg-[#f6e8de] text-[#c38f78]"
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
                                                    d="M3.75 6.75h16.5v12H3.75v-12Z"
                                                />
                                            </svg>

                                        </div>

                                    @endif


                                    <div class="p-4">

                                        <h4
                                            class="truncate font-black text-[#35272a]"
                                        >
                                            {{ $product->name }}
                                        </h4>


                                        <div
                                            class="mt-3 flex items-center justify-between gap-3"
                                        >

                                            <span
                                                class="font-black text-[#ff604c]"
                                            >
                                                S/ {{ number_format($product->price, 2) }}
                                            </span>


                                            <span
                                                class="rounded-full bg-[#fff3da] px-2.5 py-1 text-[11px] font-black text-[#806226]"
                                            >
                                                Stock {{ $product->stock }}
                                            </span>

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @endif

                </div>

            @endif

        </div>

    </div>

</x-app-layout>
