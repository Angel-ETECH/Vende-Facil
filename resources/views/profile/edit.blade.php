<x-app-layout>
    @php
        $profileUser = auth()->user();
        $primaryStore = $profileUser->stores()->first();
        $initials = collect(explode(' ', trim($profileUser->name)))
            ->filter()
            ->take(2)
            ->map(fn ($part) => mb_substr($part, 0, 1))
            ->join('');
    @endphp

    <div class="min-h-screen bg-[#fffaf5]">

        <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">

            {{-- ENCABEZADO --}}
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
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0"
                        />
                    </svg>

                    Mi cuenta
                </div>


                <h1
                    class="mt-4 text-3xl font-black tracking-[-.04em] text-[#35272a] sm:text-4xl"
                >
                    Configuración del perfil
                </h1>


                <p
                    class="mt-2 max-w-2xl text-base leading-7 text-[#806d70]"
                >
                    Actualiza tus datos personales, contraseña y opciones de seguridad.
                </p>

            </div>


            {{-- RESUMEN --}}
            <div
                class="mt-8 overflow-hidden rounded-[28px] border border-[#eaded5] bg-white shadow-[0_18px_45px_rgba(91,53,43,.07)]"
            >
                <div
                    class="grid gap-6 p-6 sm:p-8 lg:grid-cols-[1fr_auto]"
                >
                    <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
                        <div
                            class="flex h-20 w-20 flex-shrink-0 items-center justify-center rounded-[26px] bg-[#ff6b57] text-2xl font-black uppercase text-white shadow-lg shadow-orange-100"
                        >
                            {{ $initials ?: 'VF' }}
                        </div>

                        <div>
                            <p class="text-xs font-black uppercase tracking-[.14em] text-[#a48777]">
                                Cuenta de vendedor
                            </p>

                            <h2 class="mt-1 text-2xl font-black tracking-[-.035em] text-[#35272a]">
                                {{ $profileUser->name }}
                            </h2>

                            <p class="mt-1 break-all text-sm font-semibold text-[#806d70]">
                                {{ $profileUser->email }}
                            </p>

                            <div class="mt-3 flex flex-wrap gap-2">
                                <span class="inline-flex items-center rounded-full bg-[#e8f5f0] px-3 py-1 text-xs font-black text-[#3f7c69]">
                                    {{ $primaryStore ? 'Tienda creada' : 'Tienda pendiente' }}
                                </span>

                                <span class="inline-flex items-center rounded-full bg-[#fff4d7] px-3 py-1 text-xs font-black text-[#85671e]">
                                    {{ $profileUser->is_admin ? 'Administrador' : 'Vendedor' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-3 lg:w-[420px] lg:grid-cols-1">
                        <a
                            href="{{ route('dashboard') }}"
                            class="rounded-[18px] border border-[#eee2da] bg-[#fffaf4] px-4 py-3 text-sm font-black text-[#514043] transition hover:border-[#ffd2c8] hover:text-[#ff604c]"
                        >
                            Ir al panel
                        </a>

                        <a
                            href="{{ $primaryStore ? route('stores.edit') : route('stores.create') }}"
                            class="rounded-[18px] border border-[#eee2da] bg-[#fffaf4] px-4 py-3 text-sm font-black text-[#514043] transition hover:border-[#ffd2c8] hover:text-[#ff604c]"
                        >
                            {{ $primaryStore ? 'Editar mi tienda' : 'Crear mi tienda' }}
                        </a>

                        <a
                            href="{{ route('products.index') }}"
                            class="rounded-[18px] border border-[#eee2da] bg-[#fffaf4] px-4 py-3 text-sm font-black text-[#514043] transition hover:border-[#ffd2c8] hover:text-[#ff604c]"
                        >
                            Mis productos
                        </a>
                    </div>
                </div>
            </div>


            {{-- CONTENIDO --}}
            <div class="mt-8 space-y-6">

                {{-- INFORMACIÓN --}}
                <div
                    class="overflow-hidden rounded-[28px] border border-[#eaded5] bg-white shadow-[0_16px_40px_rgba(91,53,43,.07)]"
                >

                    <div
                        class="h-[6px] bg-gradient-to-r from-[#ff7f6b] via-[#ffae78] to-[#ffd66b]"
                    ></div>

                    <div class="p-6 sm:p-8">

                        @include('profile.partials.update-profile-information-form')

                    </div>

                </div>


                {{-- PASSWORD --}}
                <div
                    class="overflow-hidden rounded-[28px] border border-[#eaded5] bg-white shadow-[0_16px_40px_rgba(91,53,43,.07)]"
                >

                    <div class="p-6 sm:p-8">

                        @include('profile.partials.update-password-form')

                    </div>

                </div>


                {{-- ELIMINAR CUENTA --}}
                <div
                    class="overflow-hidden rounded-[28px] border border-[#f2d1ca] bg-white shadow-[0_16px_40px_rgba(91,53,43,.05)]"
                >

                    <div class="p-6 sm:p-8">

                        @include('profile.partials.delete-user-form')

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
