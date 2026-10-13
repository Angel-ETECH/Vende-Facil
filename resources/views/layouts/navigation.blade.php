<nav
    x-data="{ open: false }"
    class="sticky top-0 z-50 border-b border-[#eaded5] bg-[#fffaf5]/95 backdrop-blur"
>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="flex h-[74px] items-center justify-between">

            {{-- IZQUIERDA --}}
            <div class="flex items-center gap-8">

                {{-- LOGO --}}
                <a
                    href="{{ route('dashboard') }}"
                    class="flex items-center gap-3"
                >
                    <x-brand-logo size="panel" />

                </a>


                {{-- NAVEGACIÓN DESKTOP --}}
                <div class="hidden items-center gap-2 xl:flex">

                    <a
                        href="{{ route('dashboard') }}"
                        class="
                            rounded-full px-4 py-2 text-sm font-black transition

                            {{ request()->routeIs('dashboard')
                                ? 'bg-[#442d35] text-white'
                                : 'text-[#6f5b5e] hover:bg-[#f3e9e2]'
                            }}
                        "
                    >
                        Dashboard
                    </a>


                    @php
                        $navStore = auth()->user()->stores()->first();
                    @endphp


                    @if($navStore)

                        <a
                            href="{{ route('products.index') }}"
                            class="
                                rounded-full px-4 py-2 text-sm font-black transition

                                {{ request()->routeIs('products.*')
                                    ? 'bg-[#442d35] text-white'
                                    : 'text-[#6f5b5e] hover:bg-[#f3e9e2]'
                                }}
                            "
                        >
                            Productos
                        </a>


                        <a
                            href="{{ route('seller.plan.index') }}"
                            class="
                                rounded-full px-4 py-2 text-sm font-black transition

                                {{ request()->routeIs('seller.plan.*') || request()->routeIs('seller.payments.*')
                                    ? 'bg-[#442d35] text-white'
                                    : 'text-[#6f5b5e] hover:bg-[#f3e9e2]'
                                }}
                            "
                        >
                            Mi plan
                        </a>


                        <a
                            href="{{ route('stores.show', $navStore) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="rounded-full px-4 py-2 text-sm font-black text-[#6f5b5e] transition hover:bg-[#f3e9e2]"
                        >
                            Ver tienda
                        </a>

                    @endif

                    @if(auth()->user()->is_admin)

                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="
                                rounded-full px-4 py-2 text-sm font-black transition

                                {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.earnings')
                                    ? 'bg-[#ff6b57] text-white'
                                    : 'text-[#6f5b5e] hover:bg-[#f3e9e2]'
                                }}
                            "
                        >
                            Ganancias
                        </a>

                        <a
                            href="{{ route('admin.stores.index') }}"
                            class="
                                rounded-full px-4 py-2 text-sm font-black transition

                                {{ request()->routeIs('admin.stores.*')
                                    ? 'bg-[#ff6b57] text-white'
                                    : 'text-[#6f5b5e] hover:bg-[#f3e9e2]'
                                }}
                            "
                        >
                            Tiendas admin
                        </a>

                        <a
                            href="{{ route('admin.payments.index') }}"
                            class="
                                rounded-full px-4 py-2 text-sm font-black transition

                                {{ request()->routeIs('admin.payments.*')
                                    ? 'bg-[#ff6b57] text-white'
                                    : 'text-[#6f5b5e] hover:bg-[#f3e9e2]'
                                }}
                            "
                        >
                            Pagos
                        </a>

                    @endif

                </div>

            </div>


            {{-- DERECHA --}}
            <div class="hidden items-center gap-3 sm:flex">

                <div
                    x-data="{ openUser: false }"
                    class="relative"
                >

                    <button
                        type="button"
                        @click="openUser = !openUser"
                        class="flex items-center gap-3 rounded-full border border-[#eaded5] bg-white px-3 py-2 transition hover:bg-[#fff7ef]"
                    >

                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-full bg-[#ffd66b] text-sm font-black text-[#5f4823]"
                        >
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>


                        <div class="text-left">

                            <div
                                class="max-w-[150px] truncate text-sm font-black text-[#35272a]"
                            >
                                {{ Auth::user()->name }}
                            </div>

                            <div
                                class="text-[11px] font-semibold text-[#9b8583]"
                            >
                                Mi cuenta
                            </div>

                        </div>


                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                            class="h-4 w-4 text-[#8c7779] transition"
                            :class="{ 'rotate-180': openUser }"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m6 9 6 6 6-6"
                            />
                        </svg>

                    </button>


                    {{-- DROPDOWN --}}
                    <div
                        x-show="openUser"
                        x-transition
                        x-cloak
                        @click.outside="openUser = false"
                        class="absolute right-0 mt-3 w-56 overflow-hidden rounded-[18px] border border-[#eaded5] bg-white shadow-xl"
                    >

                        <div
                            class="border-b border-[#f0e3da] px-4 py-3"
                        >
                            <div
                                class="truncate text-sm font-black text-[#35272a]"
                            >
                                {{ Auth::user()->name }}
                            </div>

                            <div
                                class="mt-1 truncate text-xs text-[#927e80]"
                            >
                                {{ Auth::user()->email }}
                            </div>
                        </div>


                        <div class="p-2">

                            <a
                                href="{{ route('profile.edit') }}"
                                class="flex items-center gap-3 rounded-[12px] px-3 py-2.5 text-sm font-bold text-[#6f5b5e] transition hover:bg-[#fff5eb]"
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
                                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0"
                                    />
                                </svg>

                                Mi perfil

                            </a>


                            <a
                                href="{{ route('home') }}"
                                class="flex items-center gap-3 rounded-[12px] px-3 py-2.5 text-sm font-bold text-[#6f5b5e] transition hover:bg-[#fff5eb]"
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
                                        d="M3.75 9.75 12 4.5l8.25 5.25M5.25 8.8V19.5h13.5V8.8"
                                    />
                                </svg>

                                Ir al inicio

                            </a>


                            <form
                                method="POST"
                                action="{{ route('logout') }}"
                            >
                                @csrf

                                <button
                                    type="submit"
                                    class="flex w-full items-center gap-3 rounded-[12px] px-3 py-2.5 text-left text-sm font-bold text-[#b44f41] transition hover:bg-[#fff0ec]"
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
                                            d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12"
                                        />
                                    </svg>

                                    Cerrar sesión

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>


            {{-- BOTÓN MOBILE --}}
            <button
                type="button"
                @click="open = !open"
                :aria-expanded="open.toString()"
                :aria-label="open ? 'Cerrar menú' : 'Abrir menú'"
                aria-controls="seller-navigation"
                class="flex h-11 w-11 items-center justify-center rounded-full bg-[#f5ece5] text-[#442d35] xl:hidden"
            >

                <svg
                    x-show="!open"
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
                        d="M4 7h16M4 12h16M4 17h16"
                    />
                </svg>


                <svg
                    x-show="open"
                    x-cloak
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
                        d="m6 6 12 12M18 6 6 18"
                    />
                </svg>

            </button>

        </div>


        {{-- MOBILE --}}
        <div
            id="seller-navigation"
            x-show="open"
            x-transition
            x-cloak
            class="border-t border-[#eaded5] pb-5 pt-3 xl:hidden"
        >

            <div class="space-y-1">

                <a
                    href="{{ route('dashboard') }}"
                    class="block rounded-[12px] px-4 py-3 font-black text-[#5f4c50] hover:bg-[#f5ece5]"
                >
                    Dashboard
                </a>


                @if($navStore)

                    <a
                        href="{{ route('products.index') }}"
                        class="block rounded-[12px] px-4 py-3 font-black text-[#5f4c50] hover:bg-[#f5ece5]"
                    >
                        Productos
                    </a>


                    <a
                        href="{{ route('stores.show', $navStore) }}"
                        class="block rounded-[12px] px-4 py-3 font-black text-[#5f4c50] hover:bg-[#f5ece5]"
                    >
                        Ver tienda
                    </a>

                    <a
                        href="{{ route('seller.plan.index') }}"
                        class="block rounded-[12px] px-4 py-3 font-black text-[#5f4c50] hover:bg-[#f5ece5]"
                    >
                        Mi plan
                    </a>

                @endif

                @if(auth()->user()->is_admin)

                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="block rounded-[12px] px-4 py-3 font-black text-[#5f4c50] hover:bg-[#f5ece5]"
                    >
                        Ganancias
                    </a>

                    <a
                        href="{{ route('admin.stores.index') }}"
                        class="block rounded-[12px] px-4 py-3 font-black text-[#5f4c50] hover:bg-[#f5ece5]"
                    >
                        Tiendas admin
                    </a>

                    <a
                        href="{{ route('admin.payments.index') }}"
                        class="block rounded-[12px] px-4 py-3 font-black text-[#5f4c50] hover:bg-[#f5ece5]"
                    >
                        Pagos
                    </a>

                @endif


                <a
                    href="{{ route('profile.edit') }}"
                    class="block rounded-[12px] px-4 py-3 font-black text-[#5f4c50] hover:bg-[#f5ece5]"
                >
                    Mi perfil
                </a>


                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="mt-2 w-full rounded-[12px] bg-[#442d35] px-4 py-3 text-left font-black text-white"
                    >
                        Cerrar sesión
                    </button>

                </form>

            </div>

        </div>

    </div>

</nav>
