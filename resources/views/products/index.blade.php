<x-app-layout>

    <div
        x-data="{
            deleteModalOpen: false,
            deleteProduct: { name: '', price: '', stock: 0, url: '' },
            toggleModalOpen: false,
            toggleProduct: { name: '', active: false, url: '' },
            openDeleteModal(product) {
                this.deleteProduct = product;
                this.deleteModalOpen = true;
            },
            closeDeleteModal() {
                this.deleteModalOpen = false;
            },
            openToggleModal(product) {
                this.toggleProduct = product;
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
            {{-- ENCABEZADO --}}
            {{-- ================================================= --}}

            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

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
                                d="M6.75 4.5h10.5l1.5 15h-13.5l1.5-15ZM9 8.25a3 3 0 0 0 6 0"
                            />
                        </svg>

                        Catálogo
                    </div>


                    <h1
                        class="mt-4 text-3xl font-black tracking-[-.04em] text-[#35272a] sm:text-4xl"
                    >
                        Mis productos
                    </h1>


                    <p class="mt-2 text-base text-[#806d70]">
                        Administra los productos de
                        <span class="font-black text-[#ff604c]">
                            {{ $store->name }}
                        </span>.
                    </p>

                </div>


                <a
                    href="{{ route('products.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-full bg-[#ff6b57] px-5 py-3.5 text-sm font-black text-white shadow-lg shadow-orange-100 transition hover:bg-[#ed5542]"
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
                            d="M12 4.5v15M4.5 12h15"
                        />
                    </svg>

                    Agregar producto
                </a>

            </div>


            {{-- ================================================= --}}
            {{-- MENSAJE DE ÉXITO --}}
            {{-- ================================================= --}}

            @if (session('success'))

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


            @if (session('error'))

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
                            d="M12 9v3.75m0 3h.008v.008H12v-.008ZM12 3.75a8.25 8.25 0 1 1 0 16.5 8.25 8.25 0 0 1 0-16.5Z"
                        />
                    </svg>

                    {{ session('error') }}
                </div>

            @endif


            <div
                class="mt-6 overflow-hidden rounded-[24px] border border-[#eaded5] bg-white"
            >
                <div class="grid gap-4 p-5 lg:grid-cols-[1fr_auto] lg:items-center">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[.12em] text-[#9b8583]">
                            Plan de publicación
                        </p>

                        @if($store->unlimited_products)
                            <h2 class="mt-2 text-xl font-black tracking-[-.03em] text-[#35272a]">
                                Productos ilimitados activos
                            </h2>

                            <p class="mt-1 text-sm leading-6 text-[#806d70]">
                                Esta tienda puede publicar productos sin límite.
                            </p>
                        @else
                            <h2 class="mt-2 text-xl font-black tracking-[-.03em] text-[#35272a]">
                                {{ $productCount }} de {{ $productLimit }} productos gratis usados
                            </h2>

                            <p class="mt-1 text-sm leading-6 text-[#806d70]">
                                Te quedan {{ $remainingProductSlots }} publicaciones gratis. Luego puedes activar productos ilimitados por S/ {{ $planPrice }}.
                            </p>
                        @endif
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <span class="rounded-full bg-[#fff3da] px-4 py-2 text-xs font-black text-[#806226]">
                            Gratis: {{ $productLimit }}
                        </span>

                        <span class="rounded-full {{ $store->unlimited_products ? 'bg-[#e7f5ef] text-[#427764]' : 'bg-[#eef0f4] text-[#5f6978]' }} px-4 py-2 text-xs font-black">
                            {{ $store->unlimited_products ? 'Ilimitado activo' : 'Ilimitado disponible' }}
                        </span>

                        @unless($store->unlimited_products)
                            <a
                                href="#plan-ilimitado"
                                class="inline-flex items-center justify-center rounded-full bg-[#442d35] px-4 py-2 text-xs font-black text-white transition hover:bg-[#ff6b57]"
                            >
                                Activar productos ilimitados
                            </a>
                        @endunless
                    </div>
                </div>
            </div>


            @if(! $store->unlimited_products)
                <div id="plan-ilimitado" class="mt-6 scroll-mt-24">
                    @include('products.partials.upgrade-card')
                </div>
            @endif


            {{-- ================================================= --}}
            {{-- SIN PRODUCTOS --}}
            {{-- ================================================= --}}

            @if ($products->isEmpty())

                <div
                    class="mt-8 overflow-hidden rounded-[30px] border border-[#eaded5] bg-white shadow-[0_16px_40px_rgba(91,53,43,.07)]"
                >

                    <div
                        class="h-[6px] bg-gradient-to-r from-[#ff7f6b] via-[#ffae78] to-[#ffd66b]"
                    ></div>


                    <div class="px-6 py-14 text-center">

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
                                    d="M6.75 4.5h10.5l1.5 15h-13.5l1.5-15ZM9 8.25a3 3 0 0 0 6 0"
                                />
                            </svg>
                        </div>


                        <h2
                            class="mt-5 text-2xl font-black tracking-[-.03em] text-[#35272a]"
                        >
                            Aún no tienes productos
                        </h2>


                        <p
                            class="mx-auto mt-3 max-w-xl text-sm leading-6 text-[#806d70]"
                        >
                            Agrega tu primer producto para comenzar
                            a llenar el catálogo de tu tienda.
                        </p>


                        <a
                            href="{{ route('products.create') }}"
                            class="mt-6 inline-flex items-center gap-2 rounded-full bg-[#ff6b57] px-6 py-3.5 text-sm font-black text-white transition hover:bg-[#ed5542]"
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
                                    d="M12 4.5v15M4.5 12h15"
                                />
                            </svg>

                            Agregar primer producto
                        </a>

                    </div>

                </div>


            @else

                {{-- ================================================= --}}
                {{-- RESUMEN --}}
                {{-- ================================================= --}}

                <div
                    class="mt-8 flex flex-col gap-4 rounded-[24px] border border-[#eaded5] bg-white px-5 py-4 sm:flex-row sm:items-center sm:justify-between"
                >

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#fff1d6] text-[#a07822]"
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
                            <p class="text-xs font-bold uppercase tracking-[.1em] text-[#9b8583]">
                                Total
                            </p>

                            <p class="text-lg font-black text-[#35272a]">
                                {{ $products->count() }}
                                {{ $products->count() == 1 ? 'producto' : 'productos' }}
                            </p>
                        </div>

                    </div>


                    <a
                        href="{{ route('stores.show', $store) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 text-sm font-black text-[#4e8b79] transition hover:text-[#3d7565]"
                    >
                        Ver catálogo público

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


                {{-- ================================================= --}}
                {{-- GRID DE PRODUCTOS --}}
                {{-- ================================================= --}}

                <div
                    class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
                >

                    @foreach ($products as $product)

                        <article
                            class="group overflow-hidden rounded-[24px] border border-[#eaded5] bg-white transition duration-300 hover:-translate-y-1 hover:shadow-[0_16px_35px_rgba(91,53,43,.10)]"
                        >

                            {{-- IMAGEN --}}
                            <div
                                class="relative h-[210px] overflow-hidden bg-[#f6e8de]"
                            >

                                @if ($product->image)

                                    <img
                                        src="{{ asset('storage/' . $product->image) }}"
                                        alt="{{ $product->name }}"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.035]"
                                    >

                                @else

                                    <div
                                        class="flex h-full w-full flex-col items-center justify-center gap-3 text-[#bf8d77]"
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

                                        <span class="text-xs font-bold">
                                            Sin imagen
                                        </span>

                                    </div>

                                @endif


                                {{-- ESTADO --}}
                                @if ($product->active)

                                    <span
                                        class="absolute left-3 top-3 inline-flex items-center gap-2 rounded-full bg-white/90 px-3 py-1.5 text-[11px] font-black text-[#467a69] shadow-sm backdrop-blur"
                                    >
                                        <span
                                            class="h-2 w-2 rounded-full bg-[#69b59e]"
                                        ></span>

                                        Activo
                                    </span>

                                @else

                                    <span
                                        class="absolute left-3 top-3 rounded-full bg-[#442d35] px-3 py-1.5 text-[11px] font-black text-white shadow-sm"
                                    >
                                        Inactivo
                                    </span>

                                @endif

                            </div>


                            {{-- CONTENIDO --}}
                            <div class="p-5">

                                <h3
                                    class="truncate text-[18px] font-black tracking-[-.025em] text-[#35272a]"
                                >
                                    {{ $product->name }}
                                </h3>


                                <p
                                    class="mt-2 line-clamp-2 min-h-[40px] text-sm leading-5 text-[#806d70]"
                                >
                                    {{ $product->description ?: 'Sin descripción.' }}
                                </p>


                                <div
                                    class="mt-5 flex items-end justify-between gap-3"
                                >

                                    <div>

                                        <p
                                            class="text-[10px] font-bold uppercase tracking-[.12em] text-[#aa9094]"
                                        >
                                            Precio
                                        </p>

                                        <p
                                            class="mt-1 text-[22px] font-black text-[#ff604c]"
                                        >
                                            S/ {{ number_format($product->price, 2) }}
                                        </p>

                                    </div>


                                    <div
                                        class="rounded-full bg-[#fff3da] px-3 py-1.5 text-[11px] font-black text-[#806226]"
                                    >
                                        Stock {{ $product->stock }}
                                    </div>

                                </div>


                                {{-- ACCIONES --}}
                                <div
                                    class="mt-5 grid grid-cols-2 gap-2"
                                >

                                    <a
                                        href="{{ route('products.edit', $product) }}"
                                        class="inline-flex items-center justify-center gap-2 rounded-[14px] bg-[#fff3da] px-3 py-3 text-sm font-black text-[#806226] transition hover:bg-[#ffe9ba]"
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
                                                d="m16.862 4.487 2.651 2.651M18.75 3.75a1.875 1.875 0 0 1 2.651 2.651L8.25 19.552 3.75 20.25l.698-4.5L17.599 2.599"
                                            />
                                        </svg>

                                        Editar
                                    </a>


                                    <button
                                        type="button"
                                        class="inline-flex w-full items-center justify-center gap-2 rounded-[14px] px-3 py-3 text-sm font-black transition {{ $product->active ? 'bg-[#eef0f4] text-[#5f6978] hover:bg-[#e3e7ee]' : 'bg-[#e7f5ef] text-[#407460] hover:bg-[#d9eee5]' }}"
                                        @click="openToggleModal({
                                            name: @js($product->name),
                                            active: @js($product->active),
                                            url: @js(route('products.toggle', $product)),
                                        })"
                                    >
                                        @if($product->active)
                                            Ocultar
                                        @else
                                            Activar
                                        @endif
                                    </button>


                                    <button
                                        type="button"
                                        class="inline-flex w-full items-center justify-center gap-2 rounded-[14px] bg-[#fff0ec] px-3 py-3 text-sm font-black text-[#b65040] transition hover:bg-[#ffe3dc]"
                                        @click="openDeleteModal({
                                            name: @js($product->name),
                                            price: @js('S/ ' . number_format($product->price, 2)),
                                            stock: {{ $product->stock }},
                                            url: @js(route('products.destroy', $product)),
                                        })"
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
                                                d="M6.75 7.5h10.5M9.75 7.5V5.25h4.5V7.5m-6 0 .75 11.25h6L15.75 7.5M10.5 11.25v4.5m3-4.5v4.5"
                                            />
                                        </svg>

                                        Eliminar
                                    </button>

                                </div>

                            </div>

                        </article>

                    @endforeach

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
                        :class="toggleProduct.active ? 'bg-[#eef0f4] text-[#5f6978]' : 'bg-[#e7f5ef] text-[#427764]'"
                    >
                        <svg
                            x-show="toggleProduct.active"
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
                                d="M3 3 21 21M5.45 5.45C3.45 6.8 2.25 9 2.25 12c0 0 3.75 6.75 9.75 6.75 1.66 0 3.14-.35 4.42-.91M8.55 8.55A3 3 0 0 0 12 15a3 3 0 0 0 2.12-.88M18.56 15.56A15.2 15.2 0 0 0 21.75 12S18 5.25 12 5.25c-.94 0-1.82.12-2.64.34"
                            />
                        </svg>

                        <svg
                            x-show="!toggleProduct.active"
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
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </div>


                    <div class="min-w-0">

                        <h2
                            class="text-xl font-black tracking-[-.03em] text-[#35272a]"
                            x-text="toggleProduct.active ? 'Ocultar producto' : 'Activar producto'"
                        ></h2>

                        <p class="mt-2 text-sm leading-6 text-[#806d70]">
                            Vas a
                            <span x-text="toggleProduct.active ? 'ocultar' : 'activar'"></span>
                            <span class="font-black text-[#35272a]" x-text="toggleProduct.name"></span>.
                        </p>

                    </div>

                </div>


                <div class="mt-5 rounded-[18px] bg-[#fff8f1] px-4 py-3">
                    <p class="text-xs font-black uppercase tracking-[.08em] text-[#a08a89]">
                        Qué pasará
                    </p>
                    <p
                        class="mt-1 text-sm font-bold text-[#514043]"
                        x-text="toggleProduct.active
                            ? 'El producto dejará de mostrarse en tu tienda pública.'
                            : 'El producto volverá a mostrarse en tu tienda pública.'"
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
                        :action="toggleProduct.url"
                        method="POST"
                    >
                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="h-12 w-full rounded-[16px] px-5 text-sm font-black transition"
                            :class="toggleProduct.active
                                ? 'bg-[#5f6978] text-white hover:bg-[#4d5663]'
                                : 'bg-[#427764] text-white hover:bg-[#315c4d]'"
                            x-text="toggleProduct.active ? 'Sí, ocultar' : 'Sí, activar'"
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
                            Eliminar producto
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-[#806d70]">
                            Vas a eliminar
                            <span class="font-black text-[#35272a]" x-text="deleteProduct.name"></span>
                            de tu catálogo.
                        </p>

                    </div>

                </div>


                <div class="mt-5 rounded-[18px] bg-[#fff8f1] px-4 py-3">
                    <p class="text-xs font-black uppercase tracking-[.08em] text-[#a08a89]">
                        Producto
                    </p>
                    <div class="mt-2 flex items-center justify-between gap-4">
                        <p class="text-sm font-black text-[#514043]" x-text="deleteProduct.price"></p>
                        <p class="rounded-full bg-[#fff3da] px-3 py-1 text-xs font-black text-[#806226]">
                            Stock <span x-text="deleteProduct.stock"></span>
                        </p>
                    </div>
                    <p class="mt-2 text-xs leading-5 text-[#8f797b]">
                        Esta acción no se puede deshacer.
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
                        :action="deleteProduct.url"
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
