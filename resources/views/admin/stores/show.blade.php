<x-app-layout>

    <div
        x-data="{
            toggleModalOpen: false,
            toggleStore: {
                name: @js($store->name),
                active: @js($store->active),
                url: @js(route('admin.stores.toggle', $store)),
            },
            openToggleModal() {
                this.toggleModalOpen = true;
            },
            closeToggleModal() {
                this.toggleModalOpen = false;
            },
        }"
        class="min-h-screen bg-[#fffaf5]"
    >

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">

                <div>

                    <a
                        href="{{ route('admin.stores.index') }}"
                        class="inline-flex items-center gap-2 rounded-full border border-[#e4d6cd] bg-white px-4 py-2 text-xs font-black uppercase tracking-[.12em] text-[#6d595c] transition hover:bg-[#f8eee7]"
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

                        Volver a tiendas
                    </a>


                    <div class="mt-5 flex flex-col gap-4 sm:flex-row sm:items-center">

                        @if($store->logo)

                            <img
                                src="{{ asset('storage/' . $store->logo) }}"
                                alt="{{ $store->name }}"
                                class="h-20 w-20 rounded-[24px] border border-[#eaded5] object-cover"
                            >

                        @else

                            <div
                                class="flex h-20 w-20 items-center justify-center rounded-[24px] bg-[#ffd66b] text-3xl font-black text-[#5e4723]"
                            >
                                {{ strtoupper(substr($store->name, 0, 1)) }}
                            </div>

                        @endif


                        <div>

                            <div class="flex flex-wrap items-center gap-2">

                                @if($store->active)

                                    <span
                                        class="inline-flex items-center gap-2 rounded-full bg-[#e7f5ef] px-3 py-1.5 text-xs font-black text-[#427764]"
                                    >
                                        <span class="h-2 w-2 rounded-full bg-[#69b59e]"></span>
                                        Activa
                                    </span>

                                @else

                                    <span
                                        class="rounded-full bg-[#fff0ec] px-3 py-1.5 text-xs font-black text-[#b45040]"
                                    >
                                        Inactiva
                                    </span>

                                @endif


                                <span
                                    class="rounded-full bg-[#fff3da] px-3 py-1.5 text-xs font-black text-[#806226]"
                                >
                                    {{ $store->delivery_available ? 'Con delivery' : 'Sin delivery' }}
                                </span>

                                @if($store->isFeatured())
                                    <span
                                        class="rounded-full bg-[#f1edff] px-3 py-1.5 text-xs font-black text-[#5b49a8]"
                                    >
                                        Destacada
                                    </span>
                                @endif

                            </div>


                            <h1
                                class="mt-3 text-3xl font-black tracking-[-.04em] text-[#35272a] sm:text-4xl"
                            >
                                {{ $store->name }}
                            </h1>


                            <p class="mt-2 max-w-2xl text-base leading-7 text-[#806d70]">
                                {{ $store->description ?: 'Esta tienda todavía no tiene una descripción registrada.' }}
                            </p>

                        </div>

                    </div>

                </div>


                <div class="flex flex-col gap-2 sm:flex-row lg:justify-end">

                    @if($store->active)

                        <a
                            href="{{ route('stores.show', $store) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex h-12 items-center justify-center rounded-[16px] bg-[#e8f5f0] px-5 text-sm font-black text-[#467a69] transition hover:bg-[#d8eee6]"
                        >
                            Ver tienda pública
                        </a>

                    @else

                        <span
                            class="inline-flex h-12 cursor-not-allowed items-center justify-center rounded-[16px] bg-[#eee5df] px-5 text-sm font-black text-[#9a8583]"
                        >
                            Tienda pausada
                        </span>

                    @endif


                    <button
                        type="button"
                        class="h-12 w-full rounded-[16px] px-5 text-sm font-black transition sm:w-auto {{ $store->active ? 'bg-[#fff3da] text-[#806226] hover:bg-[#ffe9ba]' : 'bg-[#e7f5ef] text-[#427764] hover:bg-[#d8eee6]' }}"
                        @click="openToggleModal()"
                    >
                        {{ $store->active ? 'Pausar tienda' : 'Activar tienda' }}
                    </button>

                </div>

            </div>


            <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">

                <div class="rounded-[24px] border border-[#eaded5] bg-white p-5">
                    <p class="text-sm font-bold text-[#8e797b]">Productos</p>
                    <p class="mt-1 text-3xl font-black text-[#35272a]">{{ $store->products_count }}</p>
                    <p class="mt-1 text-xs font-bold text-[#927e80]">
                        {{ $store->active_products_count }} activos ·
                        {{ $store->unlimited_products ? 'ilimitado' : 'límite ' . config('vendefacil.free_product_limit') }}
                    </p>
                </div>


                <div class="rounded-[24px] border border-[#eaded5] bg-white p-5">
                    <p class="text-sm font-bold text-[#8e797b]">Visitas</p>
                    <p class="mt-1 text-3xl font-black text-[#35272a]">{{ $store->visits_count }}</p>
                    <p class="mt-1 text-xs font-bold text-[#927e80]">{{ $store->visits_today_count }} hoy</p>
                </div>


                <div class="rounded-[24px] border border-[#eaded5] bg-white p-5">
                    <p class="text-sm font-bold text-[#8e797b]">WhatsApp</p>
                    <p class="mt-1 text-3xl font-black text-[#35272a]">{{ $store->whatsapp_clicks_count }}</p>
                    <p class="mt-1 text-xs font-bold text-[#927e80]">{{ $store->whatsapp_clicks_today_count }} hoy</p>
                </div>


                <div class="rounded-[24px] border border-[#eaded5] bg-white p-5">
                    <p class="text-sm font-bold text-[#8e797b]">Registro</p>
                    <p class="mt-1 text-3xl font-black text-[#35272a]">{{ $store->created_at->format('d/m') }}</p>
                    <p class="mt-1 text-xs font-bold text-[#927e80]">{{ $store->created_at->format('Y') }}</p>
                </div>


                <div class="rounded-[24px] border border-[#eaded5] bg-white p-5">
                    <p class="text-sm font-bold text-[#8e797b]">Plan</p>
                    <p class="mt-1 text-2xl font-black text-[#35272a]">
                        {{ $store->unlimited_products ? 'Ilimitado' : 'Gratis' }}
                    </p>
                    <p class="mt-1 text-xs font-bold text-[#927e80]">
                        {{ $store->unlimited_products ? 'Productos sin límite' : config('vendefacil.free_product_limit') . ' productos gratis' }}
                    </p>
                </div>


                <div class="rounded-[24px] border border-[#eaded5] bg-white p-5">
                    <p class="text-sm font-bold text-[#8e797b]">Destacada</p>
                    <p class="mt-1 text-2xl font-black text-[#35272a]">
                        {{ $store->isFeatured() ? 'Sí' : 'No' }}
                    </p>
                    <p class="mt-1 text-xs font-bold text-[#927e80]">
                        @if($store->isFeatured())
                            Hasta {{ $store->featured_until?->format('d/m/Y') ?: 'sin fecha' }}
                        @else
                            Orden normal en el home
                        @endif
                    </p>
                </div>

            </div>


            <div class="mt-6 grid gap-6 lg:grid-cols-[0.9fr_1.5fr]">

                <div class="space-y-6">

                    <div class="rounded-[28px] border border-[#eaded5] bg-white p-6">

                        <h2 class="text-lg font-black text-[#35272a]">
                            Datos de la tienda
                        </h2>


                        <dl class="mt-5 space-y-4">

                            <div>
                                <dt class="text-xs font-black uppercase tracking-[.08em] text-[#a08a89]">Propietario</dt>
                                <dd class="mt-1 text-sm font-bold text-[#514043]">{{ $store->user->name }}</dd>
                                <dd class="mt-1 text-xs text-[#927e80]">{{ $store->user->email }}</dd>
                            </div>


                            <div>
                                <dt class="text-xs font-black uppercase tracking-[.08em] text-[#a08a89]">Ubicación</dt>
                                <dd class="mt-1 text-sm font-bold text-[#514043]">{{ $store->city ? $store->city . ', Perú' : 'Perú' }}</dd>
                                <dd class="mt-1 text-xs text-[#927e80]">{{ $store->address ?: 'Sin dirección registrada' }}</dd>
                            </div>


                            <div>
                                <dt class="text-xs font-black uppercase tracking-[.08em] text-[#a08a89]">WhatsApp</dt>
                                <dd class="mt-1 text-sm font-bold text-[#514043]">+{{ $store->whatsapp }}</dd>
                            </div>


                            <div>
                                <dt class="text-xs font-black uppercase tracking-[.08em] text-[#a08a89]">Plan de productos</dt>
                                <dd class="mt-1 text-sm font-bold text-[#514043]">
                                    {{ $store->unlimited_products ? 'Productos ilimitados' : 'Plan gratis' }}
                                </dd>
                                <dd class="mt-1 text-xs text-[#927e80]">
                                    @if($store->unlimited_products)
                                        Activado {{ $store->unlimited_products_activated_at?->format('d/m/Y H:i') ?: 'manualmente' }}
                                    @else
                                        Hasta {{ config('vendefacil.free_product_limit') }} productos sin pago.
                                    @endif
                                </dd>
                            </div>

                        </dl>

                    </div>


                    <div class="rounded-[28px] border border-[#eaded5] bg-white p-6">

                        <h2 class="text-lg font-black text-[#35272a]">
                            Acciones rápidas
                        </h2>


                        <div class="mt-5 grid gap-3">

                            @if($store->active)

                                <a
                                    href="{{ route('stores.show', $store) }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="flex items-center justify-between rounded-[18px] bg-[#e8f5f0] px-4 py-3 text-sm font-black text-[#467a69] transition hover:bg-[#d8eee6]"
                                >
                                    Ver tienda pública
                                    <span aria-hidden="true">></span>
                                </a>

                            @else

                                <span
                                    class="flex items-center justify-between rounded-[18px] bg-[#eee5df] px-4 py-3 text-sm font-black text-[#9a8583]"
                                >
                                    Tienda pública pausada
                                    <span aria-hidden="true">-</span>
                                </span>

                            @endif


                            <a
                                href="https://wa.me/{{ $store->whatsapp }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="flex items-center justify-between rounded-[18px] bg-[#e7f5ef] px-4 py-3 text-sm font-black text-[#427764] transition hover:bg-[#d8eee6]"
                            >
                                Abrir WhatsApp
                                <span aria-hidden="true">></span>
                            </a>


                            <button
                                type="button"
                                class="flex w-full items-center justify-between rounded-[18px] px-4 py-3 text-sm font-black transition {{ $store->active ? 'bg-[#fff3da] text-[#806226] hover:bg-[#ffe9ba]' : 'bg-[#e7f5ef] text-[#427764] hover:bg-[#d8eee6]' }}"
                                @click="openToggleModal()"
                            >
                                {{ $store->active ? 'Pausar tienda' : 'Activar tienda' }}
                                <span aria-hidden="true">{{ $store->active ? 'II' : '>' }}</span>
                            </button>


                            <form
                                action="{{ route('admin.stores.unlimited-products', $store) }}"
                                method="POST"
                            >
                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="flex w-full items-center justify-between rounded-[18px] px-4 py-3 text-sm font-black transition {{ $store->unlimited_products ? 'bg-[#eef0f4] text-[#5f6978] hover:bg-[#e3e7ee]' : 'bg-[#f1edff] text-[#5b49a8] hover:bg-[#e7e1ff]' }}"
                                >
                                    {{ $store->unlimited_products ? 'Quitar ilimitados' : 'Activar ilimitados' }}
                                    <span aria-hidden="true">{{ $store->unlimited_products ? '-' : '+' }}</span>
                                </button>
                            </form>


                            <form
                                action="{{ route('admin.stores.featured', $store) }}"
                                method="POST"
                            >
                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="flex w-full items-center justify-between rounded-[18px] px-4 py-3 text-sm font-black transition {{ $store->isFeatured() ? 'bg-[#eef0f4] text-[#5f6978] hover:bg-[#e3e7ee]' : 'bg-[#fff3da] text-[#806226] hover:bg-[#ffe9ba]' }}"
                                >
                                    {{ $store->isFeatured() ? 'Quitar destacada' : 'Destacar ' . config('vendefacil.featured_days') . ' días' }}
                                    <span aria-hidden="true">{{ $store->isFeatured() ? '-' : '*' }}</span>
                                </button>
                            </form>

                        </div>

                    </div>


                    <div class="rounded-[28px] border border-[#eaded5] bg-white p-6">

                        <div class="flex items-center justify-between gap-3">
                            <h2 class="text-lg font-black text-[#35272a]">
                                Pagos de la tienda
                            </h2>

                            <a
                                href="{{ route('admin.payments.index') }}"
                                class="text-xs font-black text-[#ff604c] transition hover:text-[#dc4e3d]"
                            >
                                Ver todos
                            </a>
                        </div>

                        @if($store->paymentProofs->isEmpty())
                            <p class="mt-5 rounded-[18px] bg-[#fff8f1] px-4 py-5 text-sm font-bold text-[#806d70]">
                                Esta tienda todavía no envió comprobantes.
                            </p>
                        @else
                            <div class="mt-5 space-y-3">
                                @foreach($store->paymentProofs->take(4) as $paymentProof)
                                    <div class="rounded-[18px] border border-[#eaded5] bg-[#fffdf9] px-4 py-3">
                                        <div class="flex flex-wrap items-center justify-between gap-2">
                                            <span class="rounded-full px-3 py-1 text-xs font-black {{ $paymentProof->status === 'approved' ? 'bg-[#e7f5ef] text-[#427764]' : ($paymentProof->status === 'rejected' ? 'bg-[#fff0ec] text-[#b45040]' : 'bg-[#fff3da] text-[#806226]') }}">
                                                {{ $paymentProof->statusLabel() }}
                                            </span>

                                            <a
                                                href="{{ asset('storage/' . $paymentProof->proof_path) }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="text-xs font-black text-[#496d9d]"
                                            >
                                                Archivo
                                            </a>
                                        </div>

                                        <p class="mt-2 text-sm font-black text-[#35272a]">
                                            S/ {{ number_format($paymentProof->amount, 2) }} · {{ strtoupper($paymentProof->method) }}
                                        </p>
                                        <p class="mt-1 text-xs font-bold text-[#927e80]">
                                            {{ $paymentProof->created_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                    </div>

                </div>


                <div class="space-y-6">

                    <div class="rounded-[28px] border border-[#eaded5] bg-white p-6">

                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <h2 class="text-lg font-black text-[#35272a]">
                                Productos destacados
                            </h2>

                            <span class="rounded-full bg-[#e7f5ef] px-3 py-1 text-xs font-black text-[#427764]">
                                Por WhatsApp
                            </span>
                        </div>


                        @if($topProducts->isEmpty())

                            <p class="mt-5 rounded-[18px] bg-[#fff8f1] px-4 py-5 text-sm font-bold text-[#806d70]">
                                Esta tienda todavía no tiene productos para comparar.
                            </p>

                        @else

                            <div class="mt-5 grid gap-3 sm:grid-cols-3">
                                @foreach($topProducts as $product)
                                    <div class="rounded-[20px] border border-[#eaded5] bg-[#fffdf9] p-4">
                                        <div class="flex items-center gap-3">
                                            @if($product->image)
                                                <img
                                                    src="{{ asset('storage/' . $product->image) }}"
                                                    alt="{{ $product->name }}"
                                                    class="h-12 w-12 rounded-[14px] object-cover"
                                                >
                                            @else
                                                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-[14px] bg-[#fff3da] text-sm font-black text-[#806226]">
                                                    {{ strtoupper(substr($product->name, 0, 1)) }}
                                                </div>
                                            @endif

                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-black text-[#35272a]">
                                                    {{ $product->name }}
                                                </p>
                                                <p class="mt-1 text-xs font-bold text-[#927e80]">
                                                    S/ {{ number_format($product->price, 2) }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="mt-4 flex items-end justify-between">
                                            <div>
                                                <p class="text-2xl font-black text-[#35272a]">
                                                    {{ $product->whatsapp_clicks_count }}
                                                </p>
                                                <p class="text-xs font-bold text-[#927e80]">
                                                    clicks
                                                </p>
                                            </div>

                                            @if($product->active)
                                                <span class="rounded-full bg-[#e7f5ef] px-3 py-1 text-xs font-black text-[#427764]">Activo</span>
                                            @else
                                                <span class="rounded-full bg-[#fff0ec] px-3 py-1 text-xs font-black text-[#b45040]">Oculto</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        @endif

                    </div>


                    <div class="overflow-hidden rounded-[28px] border border-[#eaded5] bg-white">

                    <div class="flex items-center justify-between border-b border-[#eaded5] bg-[#fff8f1] px-5 py-4">
                        <h2 class="text-lg font-black text-[#35272a]">
                            Productos de la tienda
                        </h2>

                        <span class="rounded-full bg-[#fff3da] px-3 py-1 text-xs font-black text-[#806226]">
                            {{ $store->products_count }} publicados
                        </span>
                    </div>


                    @if($store->products->isEmpty())

                        <div class="px-6 py-14 text-center">
                            <p class="text-lg font-black text-[#35272a]">Sin productos todavía</p>
                            <p class="mt-2 text-sm text-[#806d70]">Esta tienda aún no tiene productos registrados.</p>
                        </div>

                    @else

                        <div class="hidden overflow-x-auto md:block">

                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-[#eaded5] text-left text-xs font-black uppercase tracking-[.08em] text-[#947f80]">
                                        <th class="px-5 py-4">Producto</th>
                                        <th class="px-5 py-4">Categoría</th>
                                        <th class="px-5 py-4">Precio</th>
                                        <th class="px-5 py-4">Stock</th>
                                        <th class="px-5 py-4">WhatsApp</th>
                                        <th class="px-5 py-4">Estado</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-[#f0e3da]">
                                    @foreach($store->products as $product)
                                        <tr class="transition hover:bg-[#fffaf5]">
                                            <td class="px-5 py-4">
                                                <div class="flex items-center gap-3">
                                                    @if($product->image)
                                                        <img
                                                            src="{{ asset('storage/' . $product->image) }}"
                                                            alt="{{ $product->name }}"
                                                            class="h-12 w-12 rounded-[14px] border border-[#eaded5] object-cover"
                                                        >
                                                    @else
                                                        <div class="flex h-12 w-12 items-center justify-center rounded-[14px] bg-[#fff3da] text-sm font-black text-[#806226]">
                                                            {{ strtoupper(substr($product->name, 0, 1)) }}
                                                        </div>
                                                    @endif

                                                    <div class="min-w-0">
                                                        <p class="max-w-[220px] truncate text-sm font-black text-[#35272a]">{{ $product->name }}</p>
                                                        <p class="mt-1 max-w-[220px] truncate text-xs text-[#927e80]">{{ $product->description ?: 'Sin descripción' }}</p>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-5 py-4 text-sm font-bold text-[#514043]">
                                                {{ $product->category?->name ?: 'Sin categoría' }}
                                            </td>

                                            <td class="px-5 py-4 text-sm font-black text-[#35272a]">
                                                S/ {{ number_format($product->price, 2) }}
                                            </td>

                                            <td class="px-5 py-4 text-sm font-bold text-[#806d70]">
                                                {{ $product->stock }}
                                            </td>

                                            <td class="px-5 py-4">
                                                <span class="rounded-full bg-[#e7f5ef] px-3 py-1.5 text-xs font-black text-[#427764]">
                                                    {{ $product->whatsapp_clicks_count }}
                                                </span>
                                            </td>

                                            <td class="px-5 py-4">
                                                @if($product->active)
                                                    <span class="rounded-full bg-[#e7f5ef] px-3 py-1.5 text-xs font-black text-[#427764]">Activo</span>
                                                @else
                                                    <span class="rounded-full bg-[#fff0ec] px-3 py-1.5 text-xs font-black text-[#b45040]">Oculto</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>


                        <div class="divide-y divide-[#f0e3da] md:hidden">
                            @foreach($store->products as $product)
                                <div class="p-5">
                                    <div class="flex gap-3">
                                        @if($product->image)
                                            <img
                                                src="{{ asset('storage/' . $product->image) }}"
                                                alt="{{ $product->name }}"
                                                class="h-14 w-14 rounded-2xl object-cover"
                                            >
                                        @else
                                            <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-2xl bg-[#fff3da] text-lg font-black text-[#806226]">
                                                {{ strtoupper(substr($product->name, 0, 1)) }}
                                            </div>
                                        @endif

                                        <div class="min-w-0 flex-1">
                                            <h3 class="truncate font-black text-[#35272a]">{{ $product->name }}</h3>
                                            <p class="mt-1 text-sm font-black text-[#35272a]">S/ {{ number_format($product->price, 2) }}</p>

                                            <div class="mt-3 flex flex-wrap gap-2">
                                                <span class="rounded-full bg-[#fff3da] px-3 py-1 text-xs font-black text-[#806226]">
                                                    {{ $product->category?->name ?: 'Sin categoría' }}
                                                </span>
                                                <span class="rounded-full bg-[#e7f5ef] px-3 py-1 text-xs font-black text-[#427764]">
                                                    {{ $product->whatsapp_clicks_count }} WhatsApp
                                                </span>
                                                @if($product->active)
                                                    <span class="rounded-full bg-[#e7f5ef] px-3 py-1 text-xs font-black text-[#427764]">Activo</span>
                                                @else
                                                    <span class="rounded-full bg-[#fff0ec] px-3 py-1 text-xs font-black text-[#b45040]">Oculto</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    @endif

                    </div>

                </div>

            </div>

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

    </div>

</x-app-layout>
