<x-app-layout>

    <div
        x-data="{
            deleteModalOpen: false,
            deleteStore: { name: '', products: 0, url: '' },
            toggleModalOpen: false,
            toggleStore: { name: '', active: false, url: '' },
            openDeleteModal(store) {
                this.deleteStore = store;
                this.deleteModalOpen = true;
            },
            closeDeleteModal() {
                this.deleteModalOpen = false;
            },
            openToggleModal(store) {
                this.toggleStore = store;
                this.toggleModalOpen = true;
            },
            closeToggleModal() {
                this.toggleModalOpen = false;
            },
        }"
        class="min-h-screen bg-[#fffaf5]"
    >

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

            {{-- ================================================= --}}
            {{-- CABECERA --}}
            {{-- ================================================= --}}

            <div
                class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between"
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
                                d="M12 3.75 4.5 7.5v5.25c0 4.25 3 7.75 7.5 9 4.5-1.25 7.5-4.75 7.5-9V7.5L12 3.75Z"
                            />
                        </svg>

                        Administración
                    </div>


                    <h1
                        class="mt-4 text-3xl font-black tracking-[-.04em] text-[#35272a] sm:text-4xl"
                    >
                        Tiendas de VendeFácil
                    </h1>


                    <p
                        class="mt-2 max-w-2xl text-base leading-7 text-[#806d70]"
                    >
                        Consulta y administra todos los negocios registrados
                        dentro de la plataforma.
                    </p>

                </div>


                <a
                    href="{{ route('dashboard') }}"
                    class="inline-flex w-fit items-center gap-2 rounded-full border border-[#e4d6cd] bg-white px-5 py-3 text-sm font-black text-[#6d595c] transition hover:bg-[#f8eee7]"
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

                    Volver al panel
                </a>

            </div>


            {{-- ================================================= --}}
            {{-- MENSAJES --}}
            {{-- ================================================= --}}

            @if(session('success'))

                <div
                    class="mt-6 flex items-center gap-3 rounded-[18px] border border-[#bfe5d8] bg-[#edf8f4] px-4 py-3 text-sm font-semibold text-[#3d7764]"
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
                            d="m5 12 4 4L19 6"
                        />
                    </svg>

                    {{ session('success') }}

                </div>

            @endif


            {{-- ================================================= --}}
            {{-- ESTADÍSTICAS --}}
            {{-- ================================================= --}}

            <div
                class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6"
            >

                {{-- TOTAL TIENDAS --}}
                <div
                    class="rounded-[24px] border border-[#eaded5] bg-white p-5"
                >

                    <div class="flex items-center justify-between">

                        <div>

                            <p
                                class="text-sm font-bold text-[#8e797b]"
                            >
                                Tiendas registradas
                            </p>

                            <p
                                class="mt-1 text-3xl font-black text-[#35272a]"
                            >
                                {{ $totalStores }}
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
                                    d="M3.75 9.75 12 4.5l8.25 5.25M5.25 8.8V19.5h13.5V8.8"
                                />
                            </svg>
                        </div>

                    </div>

                </div>


                {{-- ACTIVAS --}}
                <div
                    class="rounded-[24px] border border-[#eaded5] bg-white p-5"
                >

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm font-bold text-[#8e797b]">
                                Tiendas activas
                            </p>

                            <p
                                class="mt-1 text-3xl font-black text-[#35272a]"
                            >
                                {{ $activeStores }}
                            </p>

                        </div>


                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#e7f5ef] text-[#4d8a77]"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                                stroke="currentColor"
                                class="h-6 w-6"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="m5 12 4 4L19 6"
                                />
                            </svg>
                        </div>

                    </div>

                </div>


                {{-- ILIMITADAS --}}
                <div
                    class="rounded-[24px] border border-[#eaded5] bg-white p-5"
                >

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm font-bold text-[#8e797b]">
                                Plan ilimitado
                            </p>

                            <p class="mt-1 text-3xl font-black text-[#35272a]">
                                {{ $unlimitedStores }}
                            </p>

                        </div>


                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#f1edff] text-[#5b49a8]"
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
                                    d="M12 6v12m6-6H6"
                                />
                            </svg>
                        </div>

                    </div>

                </div>

                {{-- DESTACADAS --}}
                <div
                    class="rounded-[24px] border border-[#eaded5] bg-white p-5"
                >

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm font-bold text-[#8e797b]">
                                Destacadas
                            </p>

                            <p class="mt-1 text-3xl font-black text-[#35272a]">
                                {{ $featuredStores }}
                            </p>

                        </div>


                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#fff3da] text-[#806226]"
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
                                    d="m11.48 3.5 2.46 4.99 5.5.8-3.98 3.88.94 5.48-4.92-2.59-4.92 2.59.94-5.48-3.98-3.88 5.5-.8 2.46-4.99Z"
                                />
                            </svg>
                        </div>

                    </div>

                </div>


                {{-- PAGOS PENDIENTES --}}
                <a
                    href="{{ route('admin.payments.index') }}"
                    class="rounded-[24px] border border-[#eaded5] bg-white p-5 transition hover:-translate-y-0.5 hover:shadow-[0_14px_30px_rgba(91,53,43,.08)]"
                >

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm font-bold text-[#8e797b]">
                                Pagos pendientes
                            </p>

                            <p class="mt-1 text-3xl font-black text-[#35272a]">
                                {{ $pendingPaymentProofs }}
                            </p>

                        </div>


                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#e7f5ef] text-[#427764]"
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
                                    d="M2.25 18.75h19.5M3.75 6.75h16.5v9H3.75v-9Zm3 3h.008v.008H6.75V9.75Zm10.5 3h.008v.008h-.008v-.008Z"
                                />
                            </svg>
                        </div>

                    </div>

                </a>


                {{-- PRODUCTOS --}}
                <div
                    class="rounded-[24px] border border-[#eaded5] bg-white p-5"
                >

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm font-bold text-[#8e797b]">
                                Productos publicados
                            </p>

                            <p
                                class="mt-1 text-3xl font-black text-[#35272a]"
                            >
                                {{ $totalProducts }}
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
                                    d="M6.75 4.5h10.5l1.5 15h-13.5l1.5-15ZM9 8.25a3 3 0 0 0 6 0"
                                />
                            </svg>
                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- BUSCADOR --}}
            {{-- ================================================= --}}

            <div
                class="mt-6 rounded-[24px] border border-[#eaded5] bg-white p-4"
            >

                <form
                    action="{{ route('admin.stores.index') }}"
                    method="GET"
                    class="grid gap-3 lg:grid-cols-[minmax(0,1.6fr)_repeat(3,minmax(150px,1fr))_auto]"
                >

                    <div class="relative flex-1">

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
                            name="buscar"
                            value="{{ $search }}"
                            placeholder="Buscar por tienda, propietario, ciudad, correo, WhatsApp..."
                            class="h-14 w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] pl-12 pr-4 text-sm font-semibold text-[#3d3032] placeholder:text-[#b7a3a0] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                        >

                    </div>


                    <select
                        name="ciudad"
                        class="h-14 rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 text-sm font-black text-[#514043] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                    >
                        <option value="">Todas las ciudades</option>

                        @foreach($cities as $filterCity)
                            <option
                                value="{{ $filterCity }}"
                                @selected($city === $filterCity)
                            >
                                {{ $filterCity }}
                            </option>
                        @endforeach
                    </select>


                    <select
                        name="estado"
                        class="h-14 rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 text-sm font-black text-[#514043] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                    >
                        <option value="todos" @selected($status === 'todos')>
                            Todos los estados
                        </option>
                        <option value="activa" @selected($status === 'activa')>
                            Activas
                        </option>
                        <option value="inactiva" @selected($status === 'inactiva')>
                            Inactivas
                        </option>
                    </select>


                    <select
                        name="delivery"
                        class="h-14 rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 text-sm font-black text-[#514043] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                    >
                        <option value="todos" @selected($delivery === 'todos')>
                            Todo delivery
                        </option>
                        <option value="si" @selected($delivery === 'si')>
                            Con delivery
                        </option>
                        <option value="no" @selected($delivery === 'no')>
                            Sin delivery
                        </option>
                    </select>


                    <button
                        type="submit"
                        class="h-14 rounded-[16px] bg-[#442d35] px-6 text-sm font-black text-white transition hover:bg-[#ff6b57]"
                    >
                        Buscar
                    </button>


                    @if($search || $city || $status !== 'todos' || $delivery !== 'todos')

                        <a
                            href="{{ route('admin.stores.index') }}"
                            class="inline-flex h-14 items-center justify-center rounded-[16px] border border-[#e4d6cd] px-5 text-sm font-black text-[#78666a] transition hover:bg-[#f8eee7] lg:col-start-5"
                        >
                            Limpiar
                        </a>

                    @endif

                </form>

            </div>


            {{-- ================================================= --}}
            {{-- TIENDAS --}}
            {{-- ================================================= --}}

            @if($stores->isEmpty())

                <div
                    class="mt-6 rounded-[28px] border-2 border-dashed border-[#dcc9bb] bg-white px-6 py-14 text-center"
                >

                    <h2
                        class="text-xl font-black text-[#35272a]"
                    >
                        No encontramos tiendas
                    </h2>

                    <p
                        class="mt-2 text-sm text-[#806d70]"
                    >
                        No hay resultados para la búsqueda realizada.
                    </p>

                </div>

            @else

                <div
                    class="mt-6 overflow-hidden rounded-[28px] border border-[#eaded5] bg-white"
                >

                    {{-- DESKTOP --}}
                    <div class="hidden overflow-x-auto md:block">

                        <table class="w-full min-w-[1260px]">

                            <thead
                                class="border-b border-[#eaded5] bg-[#fff8f1]"
                            >

                                <tr
                                    class="text-left text-xs font-black uppercase tracking-[.08em] text-[#947f80]"
                                >
                                    <th class="min-w-[290px] px-6 py-4">
                                        Tienda
                                    </th>

                                    <th class="min-w-[230px] px-5 py-4">
                                        Propietario
                                    </th>

                                    <th class="px-5 py-4">
                                        Productos
                                    </th>

                                    <th class="min-w-[180px] px-5 py-4">
                                        Métricas
                                    </th>

                                    <th class="px-5 py-4">
                                        Estado
                                    </th>

                                    <th class="px-5 py-4">
                                        Registrada
                                    </th>

                                    <th class="min-w-[210px] px-6 py-4 text-right">
                                        Acciones
                                    </th>
                                </tr>

                            </thead>


                            <tbody class="divide-y divide-[#f0e3da]">

                                @foreach($stores as $store)

                                    <tr
                                        class="transition hover:bg-[#fffaf5]"
                                    >

                                        {{-- TIENDA --}}
                                        <td class="px-6 py-4">

                                            <div
                                                class="flex items-center gap-3"
                                            >

                                                @if($store->logo)

                                                    <img
                                                        src="{{ asset('storage/' . $store->logo) }}"
                                                        alt="{{ $store->name }}"
                                                        class="h-12 w-12 rounded-[14px] border border-[#eaded5] object-cover"
                                                    >

                                                @else

                                                    <div
                                                        class="flex h-12 w-12 items-center justify-center rounded-[14px] bg-[#ffd66b] font-black text-[#5e4723]"
                                                    >
                                                        {{ strtoupper(substr($store->name, 0, 1)) }}
                                                    </div>

                                                @endif


                                                <div>

                                                    <p
                                                        class="font-black text-[#35272a]"
                                                    >
                                                        {{ $store->name }}
                                                    </p>

                                                    @if($store->isFeatured())
                                                        <span
                                                            class="mt-2 inline-flex w-fit rounded-full bg-[#fff3da] px-3 py-1 text-[11px] font-black text-[#806226]"
                                                        >
                                                            Destacada
                                                        </span>
                                                    @endif

                                                    <p
                                                        class="mt-1 max-w-[220px] truncate text-xs text-[#927e80]"
                                                    >
                                                        {{ $store->city ? $store->city . ', Perú' : 'Perú' }}
                                                        @if($store->address)
                                                            · {{ $store->address }}
                                                        @endif
                                                    </p>

                                                </div>

                                            </div>

                                        </td>


                                        {{-- PROPIETARIO --}}
                                        <td class="px-5 py-4">

                                            <p
                                                class="text-sm font-bold text-[#514043]"
                                            >
                                                {{ $store->user->name }}
                                            </p>

                                            <p
                                                class="mt-1 text-xs text-[#927e80]"
                                            >
                                                {{ $store->user->email }}
                                            </p>

                                        </td>


                                        {{-- PRODUCTOS --}}
                                        <td class="px-5 py-4">

                                            <div class="flex flex-col gap-2">
                                                <span
                                                    class="w-fit rounded-full bg-[#fff3da] px-3 py-1.5 text-xs font-black text-[#806226]"
                                                >
                                                    {{ $store->products_count }}
                                                </span>

                                                <span
                                                    class="w-fit rounded-full px-3 py-1.5 text-[11px] font-black {{ $store->unlimited_products ? 'bg-[#f1edff] text-[#5b49a8]' : 'bg-[#eef0f4] text-[#5f6978]' }}"
                                                >
                                                    {{ $store->unlimited_products ? 'Ilimitado' : 'Gratis ' . config('vendefacil.free_product_limit') }}
                                                </span>
                                            </div>

                                        </td>


                                        {{-- MÉTRICAS --}}
                                        <td class="px-5 py-4 align-middle">

                                            <div class="space-y-2">

                                                <div class="flex items-center gap-2">

                                                    <span
                                                        class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-[10px] bg-[#eef4ff] text-[#496d9d]"
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
                                                                d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"
                                                            />
                                                            <circle
                                                                cx="12"
                                                                cy="12"
                                                                r="3"
                                                            />
                                                        </svg>
                                                    </span>

                                                    <div>
                                                        <p class="text-sm font-black text-[#35272a]">
                                                            {{ $store->visits_count }}
                                                        </p>
                                                        <p class="whitespace-nowrap text-[11px] font-bold text-[#927e80]">
                                                            visitas · {{ $store->visits_today_count }} hoy
                                                        </p>
                                                    </div>

                                                </div>


                                                <div class="flex items-center gap-2">

                                                    <span
                                                        class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-[10px] bg-[#e7f5ef] text-[#427764]"
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
                                                                d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm3.75 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm3.75 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"
                                                            />
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="M21 11.25c0 4.142-4.03 7.5-9 7.5a10.4 10.4 0 0 1-3.36-.55L3 19.5l1.6-3.74A6.9 6.9 0 0 1 3 11.25c0-4.142 4.03-7.5 9-7.5s9 3.358 9 7.5Z"
                                                            />
                                                        </svg>
                                                    </span>

                                                    <div>
                                                        <p class="text-sm font-black text-[#35272a]">
                                                            {{ $store->whatsapp_clicks_count }}
                                                        </p>
                                                        <p class="whitespace-nowrap text-[11px] font-bold text-[#927e80]">
                                                            WhatsApp · {{ $store->whatsapp_clicks_today_count }} hoy
                                                        </p>
                                                    </div>

                                                </div>

                                            </div>

                                        </td>


                                        {{-- ESTADO --}}
                                        <td class="px-5 py-4">

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

                                        </td>


                                        {{-- FECHA --}}
                                        <td
                                            class="px-5 py-4 text-sm text-[#806d70]"
                                        >
                                            {{ $store->created_at->format('d/m/Y') }}
                                        </td>


                                        {{-- ACCIONES --}}
                                        <td class="px-6 py-4">

                                            <div
                                                class="flex items-center justify-end gap-2"
                                            >

                                                <a
                                                    href="{{ route('admin.stores.show', $store) }}"
                                                    title="Ver detalle admin"
                                                    class="flex h-10 w-10 items-center justify-center rounded-[12px] bg-[#eef4ff] text-[#496d9d] transition hover:bg-[#dde9ff]"
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
                                                            d="M19.5 14.25v-6a2.25 2.25 0 0 0-2.25-2.25H6.75A2.25 2.25 0 0 0 4.5 8.25v7.5A2.25 2.25 0 0 0 6.75 18h6"
                                                        />
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="m15.75 17.25 1.5 1.5 3-3"
                                                        />
                                                    </svg>
                                                </a>


                                                @if($store->active)

                                                    <a
                                                        href="{{ route('stores.show', $store) }}"
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        title="Ver tienda"
                                                        class="flex h-10 w-10 items-center justify-center rounded-[12px] bg-[#e8f5f0] text-[#467a69] transition hover:bg-[#d8eee6]"
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
                                                                d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"
                                                            />
                                                            <circle
                                                                cx="12"
                                                                cy="12"
                                                                r="3"
                                                            />
                                                        </svg>
                                                    </a>

                                                @else

                                                    <span
                                                        title="Tienda pausada"
                                                        class="flex h-10 w-10 cursor-not-allowed items-center justify-center rounded-[12px] bg-[#eee5df] text-[#9a8583]"
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
                                                                d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"
                                                            />
                                                            <circle
                                                                cx="12"
                                                                cy="12"
                                                                r="3"
                                                            />
                                                        </svg>
                                                    </span>

                                                @endif


                                                <button
                                                    type="button"
                                                    title="{{ $store->active ? 'Pausar tienda' : 'Activar tienda' }}"
                                                    class="flex h-10 w-10 items-center justify-center rounded-[12px] transition {{ $store->active ? 'bg-[#fff3da] text-[#806226] hover:bg-[#ffe9ba]' : 'bg-[#e7f5ef] text-[#427764] hover:bg-[#d8eee6]' }}"
                                                    @click="openToggleModal({
                                                        name: @js($store->name),
                                                        active: @js($store->active),
                                                        url: @js(route('admin.stores.toggle', $store)),
                                                    })"
                                                >
                                                    @if($store->active)
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
                                                                d="M9.75 5.25v13.5m4.5-13.5v13.5"
                                                            />
                                                        </svg>
                                                    @else
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
                                                                d="M5.25 5.25 18.75 12 5.25 18.75V5.25Z"
                                                            />
                                                        </svg>
                                                    @endif
                                                </button>


                                                <button
                                                    type="button"
                                                    title="Eliminar tienda"
                                                    class="flex h-10 w-10 items-center justify-center rounded-[12px] bg-[#fff0ec] text-[#b65040] transition hover:bg-[#ffe0d8]"
                                                    @click="openDeleteModal({
                                                        name: @js($store->name),
                                                        products: {{ $store->products_count }},
                                                        url: @js(route('admin.stores.destroy', $store)),
                                                    })"
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
                                                            d="M6.75 7.5h10.5M9.75 7.5V5.25h4.5V7.5m-6 0 .75 11.25h6L15.75 7.5M10.5 11.25v4.5m3-4.5v4.5"
                                                        />
                                                    </svg>
                                                </button>

                                            </div>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    {{-- MOBILE --}}
                    <div
                        class="divide-y divide-[#f0e3da] md:hidden"
                    >

                        @foreach($stores as $store)

                            <div class="p-5">

                                <div
                                    class="flex items-start gap-3"
                                >

                                    @if($store->logo)

                                        <img
                                            src="{{ asset('storage/' . $store->logo) }}"
                                            alt="{{ $store->name }}"
                                            class="h-14 w-14 rounded-2xl object-cover"
                                        >

                                    @else

                                        <div
                                            class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-2xl bg-[#ffd66b] text-xl font-black"
                                        >
                                            {{ strtoupper(substr($store->name, 0, 1)) }}
                                        </div>

                                    @endif


                                    <div class="min-w-0 flex-1">

                                        <h3
                                            class="truncate font-black text-[#35272a]"
                                        >
                                            {{ $store->name }}
                                        </h3>

                                        <p
                                            class="mt-1 truncate text-xs text-[#927e80]"
                                        >
                                            {{ $store->user->name }}
                                        </p>

                                        <div
                                            class="mt-3 flex flex-wrap gap-2"
                                        >

                                            <span
                                                class="rounded-full bg-[#fff3da] px-3 py-1 text-xs font-black text-[#806226]"
                                            >
                                                {{ $store->products_count }}
                                                productos
                                            </span>

                                            <span
                                                class="rounded-full px-3 py-1 text-xs font-black {{ $store->unlimited_products ? 'bg-[#f1edff] text-[#5b49a8]' : 'bg-[#eef0f4] text-[#5f6978]' }}"
                                            >
                                                {{ $store->unlimited_products ? 'Ilimitado' : 'Gratis' }}
                                            </span>

                                            @if($store->active)

                                                <span
                                                    class="rounded-full bg-[#e7f5ef] px-3 py-1 text-xs font-black text-[#427764]"
                                                >
                                                    Activa
                                                </span>

                                            @else

                                                <span
                                                    class="rounded-full bg-[#fff0ec] px-3 py-1 text-xs font-black text-[#b45040]"
                                                >
                                                    Inactiva
                                                </span>

                                            @endif


                                            <span
                                                class="rounded-full bg-[#eef4ff] px-3 py-1 text-xs font-black text-[#496d9d]"
                                            >
                                                {{ $store->visits_count }}
                                                visitas
                                            </span>


                                            <span
                                                class="rounded-full bg-[#e7f5ef] px-3 py-1 text-xs font-black text-[#427764]"
                                            >
                                                {{ $store->whatsapp_clicks_count }}
                                                WhatsApp
                                            </span>

                                        </div>

                                    </div>

                                </div>


                                <div
                                    class="mt-4 grid grid-cols-2 gap-2"
                                >

                                    <a
                                        href="{{ route('admin.stores.show', $store) }}"
                                        class="rounded-[14px] bg-[#eef4ff] px-4 py-3 text-center text-sm font-black text-[#496d9d]"
                                    >
                                        Detalle
                                    </a>


                                    @if($store->active)

                                        <a
                                            href="{{ route('stores.show', $store) }}"
                                            target="_blank"
                                            class="rounded-[14px] bg-[#e8f5f0] px-4 py-3 text-center text-sm font-black text-[#467a69]"
                                        >
                                            Ver tienda
                                        </a>

                                    @else

                                        <span
                                            class="rounded-[14px] bg-[#eee5df] px-4 py-3 text-center text-sm font-black text-[#9a8583]"
                                        >
                                            Pausada
                                        </span>

                                    @endif


                                    <button
                                        type="button"
                                        class="w-full rounded-[14px] px-4 py-3 text-sm font-black {{ $store->active ? 'bg-[#fff3da] text-[#806226]' : 'bg-[#e7f5ef] text-[#427764]' }}"
                                        @click="openToggleModal({
                                            name: @js($store->name),
                                            active: @js($store->active),
                                            url: @js(route('admin.stores.toggle', $store)),
                                        })"
                                    >
                                        {{ $store->active ? 'Pausar' : 'Activar' }}
                                    </button>


                                    <button
                                        type="button"
                                        class="col-span-2 w-full rounded-[14px] bg-[#fff0ec] px-4 py-3 text-sm font-black text-[#b65040]"
                                        @click="openDeleteModal({
                                            name: @js($store->name),
                                            products: {{ $store->products_count }},
                                            url: @js(route('admin.stores.destroy', $store)),
                                        })"
                                    >
                                        Eliminar
                                    </button>

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>


                <div class="mt-6">
                    {{ $stores->links() }}
                </div>

            @endif

        </div>


        <div
            x-cloak
            x-show="toggleModalOpen"
            x-transition.opacity
            class="fixed inset-0 z-50 flex items-end justify-center bg-[#2c2023]/50 px-4 py-6 backdrop-blur-sm sm:items-center"
            @keydown.escape.window="closeToggleModal()"
        >

            <div
                x-show="toggleModalOpen"
                x-transition
                class="w-full max-w-md rounded-[28px] border border-[#eaded5] bg-white p-6 shadow-2xl"
                @click.outside="closeToggleModal()"
            >

                <div class="flex items-start gap-4">

                    <div
                        class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-2xl"
                        :class="toggleStore.active ? 'bg-[#fff3da] text-[#806226]' : 'bg-[#e7f5ef] text-[#427764]'"
                    >
                        <svg
                            x-show="toggleStore.active"
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
                                d="M9.75 5.25v13.5m4.5-13.5v13.5"
                            />
                        </svg>

                        <svg
                            x-show="!toggleStore.active"
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
                                d="M5.25 5.25 18.75 12 5.25 18.75V5.25Z"
                            />
                        </svg>
                    </div>


                    <div class="min-w-0">

                        <h2
                            class="text-xl font-black tracking-[-.03em] text-[#35272a]"
                            x-text="toggleStore.active ? 'Pausar tienda' : 'Activar tienda'"
                        ></h2>

                        <p class="mt-2 text-sm leading-6 text-[#806d70]">
                            Vas a
                            <span x-text="toggleStore.active ? 'pausar' : 'activar'"></span>
                            <span class="font-black text-[#35272a]" x-text="toggleStore.name"></span>.
                        </p>

                    </div>

                </div>


                <div class="mt-5 rounded-[18px] bg-[#fff8f1] px-4 py-3">
                    <p class="text-xs font-black uppercase tracking-[.08em] text-[#a08a89]">
                        Qué pasará
                    </p>
                    <p
                        class="mt-1 text-sm font-bold text-[#514043]"
                        x-text="toggleStore.active
                            ? 'La tienda dejará de mostrarse al público hasta que la vuelvas a activar.'
                            : 'La tienda volverá a mostrarse al público y podrá recibir visitas.'"
                    ></p>
                </div>


                <div class="mt-6 grid gap-3 sm:grid-cols-2">

                    <button
                        type="button"
                        class="h-12 rounded-[16px] border border-[#e4d6cd] px-5 text-sm font-black text-[#78666a] transition hover:bg-[#f8eee7]"
                        @click="closeToggleModal()"
                    >
                        Cancelar
                    </button>


                    <form
                        :action="toggleStore.url"
                        method="POST"
                    >
                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="h-12 w-full rounded-[16px] px-5 text-sm font-black transition"
                            :class="toggleStore.active
                                ? 'bg-[#806226] text-white hover:bg-[#6a501d]'
                                : 'bg-[#427764] text-white hover:bg-[#315c4d]'"
                            x-text="toggleStore.active ? 'Sí, pausar' : 'Sí, activar'"
                        ></button>
                    </form>

                </div>

            </div>

        </div>


        <div
            x-cloak
            x-show="deleteModalOpen"
            x-transition.opacity
            class="fixed inset-0 z-50 flex items-end justify-center bg-[#2c2023]/50 px-4 py-6 backdrop-blur-sm sm:items-center"
            @keydown.escape.window="closeDeleteModal()"
        >

            <div
                x-show="deleteModalOpen"
                x-transition
                class="w-full max-w-md rounded-[28px] border border-[#eaded5] bg-white p-6 shadow-2xl"
                @click.outside="closeDeleteModal()"
            >

                <div class="flex items-start gap-4">

                    <div
                        class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-2xl bg-[#fff0ec] text-[#b65040]"
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
                                d="M12 9v4.5m0 3h.01M4.5 19.5h15L12 4.5l-7.5 15Z"
                            />
                        </svg>
                    </div>


                    <div class="min-w-0">

                        <h2 class="text-xl font-black tracking-[-.03em] text-[#35272a]">
                            Eliminar tienda
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-[#806d70]">
                            Vas a eliminar
                            <span class="font-black text-[#35272a]" x-text="deleteStore.name"></span>.
                            También se eliminarán sus productos e imágenes asociadas.
                        </p>

                    </div>

                </div>


                <div class="mt-5 rounded-[18px] bg-[#fff8f1] px-4 py-3">
                    <p class="text-xs font-black uppercase tracking-[.08em] text-[#a08a89]">
                        Impacto
                    </p>
                    <p class="mt-1 text-sm font-bold text-[#514043]">
                        <span x-text="deleteStore.products"></span>
                        productos serán eliminados junto con esta tienda.
                    </p>
                </div>


                <div class="mt-6 grid gap-3 sm:grid-cols-2">

                    <button
                        type="button"
                        class="h-12 rounded-[16px] border border-[#e4d6cd] px-5 text-sm font-black text-[#78666a] transition hover:bg-[#f8eee7]"
                        @click="closeDeleteModal()"
                    >
                        Cancelar
                    </button>


                    <form
                        :action="deleteStore.url"
                        method="POST"
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="h-12 w-full rounded-[16px] bg-[#b65040] px-5 text-sm font-black text-white transition hover:bg-[#963b30]"
                        >
                            Sí, eliminar
                        </button>
                    </form>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
