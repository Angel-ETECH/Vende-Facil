@extends('layouts.public')

@section('title', 'Crear cuenta - VendeFácil')

@section('description')
Crea tu cuenta en VendeFácil y empieza a publicar tu negocio.
@endsection


@section('content')

<section class="relative min-h-[calc(100vh-74px)] overflow-hidden bg-[#fff3e5]">

    {{-- DECORACIÓN --}}
    <div
        class="absolute -left-28 top-14 h-72 w-72 rounded-full bg-[#ffd66b]/35"
    ></div>

    <div
        class="absolute -right-24 top-20 h-80 w-80 rounded-full bg-[#ffb8c8]/45"
    ></div>

    <div
        class="absolute bottom-10 left-[42%] h-36 w-36 rounded-full bg-[#98dbc6]/25 blur-xl"
    ></div>


    <div
        class="vf-container relative grid min-h-[calc(100vh-74px)] items-center gap-12 py-12 lg:grid-cols-[.9fr_1.1fr]"
    >

        {{-- ===================================================== --}}
        {{-- IZQUIERDA --}}
        {{-- ===================================================== --}}

        <div class="hidden lg:block">

            <div
                class="inline-flex items-center gap-2 rounded-full bg-[#ffd66b] px-4 py-2 text-sm font-black text-[#5f4823]"
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
                        d="M12 4.5v15m7.5-7.5h-15"
                    />
                </svg>

                Empieza hoy
            </div>


            <h1
                class="mt-6 max-w-xl text-[54px] font-black leading-[1] tracking-[-.055em] text-[#35272a]"
            >
                Tu negocio merece

                <span class="text-[#ff604c]">
                    un espacio propio.
                </span>
            </h1>


            <p
                class="mt-6 max-w-lg text-lg leading-8 text-[#756366]"
            >
                Crea tu cuenta, publica tus productos y permite que
                nuevas personas descubran tu tienda.
            </p>


            <div class="mt-10 space-y-5">

                <div class="flex items-start gap-4">

                    <div
                        class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-2xl bg-white text-[#ff6b57] shadow-sm"
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
                                d="M4.5 19.5h15v-9.75L12 4.5 4.5 9.75V19.5Z"
                            />
                        </svg>
                    </div>

                    <div>
                        <h3 class="font-black text-[#403033]">
                            Crea tu tienda
                        </h3>

                        <p class="mt-1 text-sm leading-6 text-[#806d70]">
                            Personaliza tu negocio con nombre, logo,
                            descripción y datos de contacto.
                        </p>
                    </div>

                </div>


                <div class="flex items-start gap-4">

                    <div
                        class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-2xl bg-white text-[#b58a2e] shadow-sm"
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
                        <h3 class="font-black text-[#403033]">
                            Publica tus productos
                        </h3>

                        <p class="mt-1 text-sm leading-6 text-[#806d70]">
                            Agrega fotos, precios, descripción y stock
                            de todos tus productos.
                        </p>
                    </div>

                </div>


                <div class="flex items-start gap-4">

                    <div
                        class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-2xl bg-white text-[#4e8b79] shadow-sm"
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

                    <div>
                        <h3 class="font-black text-[#403033]">
                            Recibe pedidos
                        </h3>

                        <p class="mt-1 text-sm leading-6 text-[#806d70]">
                            Tus clientes podrán contactarte directamente
                            por WhatsApp.
                        </p>
                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- FORMULARIO --}}
        {{-- ===================================================== --}}

        <div
            class="mx-auto w-full max-w-[560px]"
        >

            <div
                class="overflow-hidden rounded-[32px] border border-[#eaded5] bg-white shadow-[0_24px_60px_rgba(91,53,43,.12)]"
            >

                <div
                    class="h-[6px] bg-gradient-to-r from-[#ff7f6b] via-[#ffae78] to-[#ffd66b]"
                ></div>


                <div class="p-6 sm:p-8">

                    {{-- HEADER --}}
                    <div>

                        <a
                            href="{{ route('home') }}"
                            class="mb-6 inline-flex items-center gap-3 rounded-full border border-[#efe2d8] bg-[#fffaf4] px-3 py-2 text-sm font-black text-[#3d3032] transition hover:border-[#ffd2c8] hover:text-[#ff604c]"
                        >
                            <span
                                class="flex h-9 w-9 items-center justify-center rounded-full bg-[#ff6b57] text-white shadow-sm"
                            >
                                VF
                            </span>

                            VendeFácil
                        </a>

                        <h2
                            class="text-3xl font-black tracking-[-.04em] text-[#35272a]"
                        >
                            Crea tu cuenta de vendedor
                        </h2>

                        <p
                            class="mt-2 text-sm leading-6 text-[#806d70]"
                        >
                            Empieza con tu acceso y luego completa los datos de tu negocio peruano.
                        </p>


                        <div
                            class="mt-5 grid gap-3 sm:grid-cols-3"
                        >
                            <div class="rounded-[18px] border border-[#f1e4da] bg-[#fffaf4] px-4 py-3">
                                <p class="text-[11px] font-black uppercase tracking-[.14em] text-[#a48777]">
                                    Ubicación
                                </p>
                                <p class="mt-1 text-sm font-black text-[#3d3032]">
                                    Perú
                                </p>
                            </div>

                            <div class="rounded-[18px] border border-[#f1e4da] bg-[#fffaf4] px-4 py-3">
                                <p class="text-[11px] font-black uppercase tracking-[.14em] text-[#a48777]">
                                    Publicación
                                </p>
                                <p class="mt-1 text-sm font-black text-[#3d3032]">
                                    Gratis
                                </p>
                            </div>

                            <div class="rounded-[18px] border border-[#f1e4da] bg-[#fffaf4] px-4 py-3">
                                <p class="text-[11px] font-black uppercase tracking-[.14em] text-[#a48777]">
                                    Pedidos
                                </p>
                                <p class="mt-1 text-sm font-black text-[#3d3032]">
                                    WhatsApp
                                </p>
                            </div>
                        </div>

                    </div>


                    {{-- ERRORES GENERALES --}}
                    @if ($errors->any())

                        <div
                            class="mt-6 rounded-[18px] border border-[#f3c5bc] bg-[#fff0ec] px-4 py-3 text-sm text-[#a24536]"
                        >

                            <div class="flex items-start gap-3">

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
                                        d="M12 9v3.75m9-1.5a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM12 15.75h.008v.008H12v-.008Z"
                                    />
                                </svg>

                                <div>
                                    <p class="font-black">
                                        Revisa la información ingresada.
                                    </p>
                                </div>

                            </div>

                        </div>

                    @endif


                    <form
                        method="POST"
                        action="{{ route('register') }}"
                        class="mt-7 space-y-5"
                    >

                        @csrf


                        {{-- NOMBRE --}}
                        <div>

                            <label
                                for="name"
                                class="mb-2 block text-sm font-black text-[#514043]"
                            >
                                Nombre
                            </label>


                            <div class="relative">

                                <div
                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-[#b19795]"
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
                                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0"
                                        />
                                    </svg>
                                </div>


                                <input
                                    id="name"
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    autofocus
                                    autocomplete="name"
                                    placeholder="Tu nombre"
                                    class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] py-3.5 pl-12 pr-4 text-[#3d3032] placeholder:text-[#b7a3a0] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                                >

                            </div>


                            @error('name')
                                <p class="mt-2 text-sm font-semibold text-[#c34d3c]">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- EMAIL --}}
                        <div>

                            <label
                                for="email"
                                class="mb-2 block text-sm font-black text-[#514043]"
                            >
                                Correo electrónico
                            </label>


                            <div class="relative">

                                <div
                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-[#b19795]"
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
                                            d="M21.75 6.75v10.5A2.25 2.25 0 0 1 19.5 19.5h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0-8.69 5.79a1.9 1.9 0 0 1-2.12 0L2.25 6.75"
                                        />
                                    </svg>
                                </div>


                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autocomplete="username"
                                    placeholder="correo@ejemplo.com"
                                    class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] py-3.5 pl-12 pr-4 text-[#3d3032] placeholder:text-[#b7a3a0] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                                >

                            </div>


                            @error('email')
                                <p class="mt-2 text-sm font-semibold text-[#c34d3c]">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- PASSWORD --}}
                        <div
                            x-data="{ showPassword: false }"
                        >

                            <label
                                for="password"
                                class="mb-2 block text-sm font-black text-[#514043]"
                            >
                                Contraseña
                            </label>


                            <div class="relative">

                                <div
                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-[#b19795]"
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
                                            d="M16.5 10.5V6.75a4.5 4.5 0 0 0-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0 1 19.5 12.75v6A2.25 2.25 0 0 1 17.25 21H6.75A2.25 2.25 0 0 1 4.5 18.75v-6A2.25 2.25 0 0 1 6.75 10.5Z"
                                        />
                                    </svg>
                                </div>


                                <input
                                    id="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    name="password"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Mínimo 8 caracteres"
                                    class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] py-3.5 pl-12 pr-12 text-[#3d3032] placeholder:text-[#b7a3a0] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                                >


                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-[#9d8888] transition hover:text-[#ff6b57]"
                                >
                                    <svg
                                        x-show="!showPassword"
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
                                            d="M2.25 12s3.75-6 9.75-6 9.75 6 9.75 6-3.75 6-9.75 6-9.75-6-9.75-6Z"
                                        />
                                        <circle cx="12" cy="12" r="2.5"/>
                                    </svg>


                                    <svg
                                        x-show="showPassword"
                                        x-cloak
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
                                            d="M3 3l18 18M10.6 6.17A9.3 9.3 0 0 1 12 6c6 0 9.75 6 9.75 6a16.6 16.6 0 0 1-2.7 3.24M6.3 6.3C3.72 8 2.25 12 2.25 12S6 18 12 18c1.2 0 2.31-.24 3.3-.65"
                                        />
                                    </svg>
                                </button>

                            </div>


                            @error('password')
                                <p class="mt-2 text-sm font-semibold text-[#c34d3c]">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- CONFIRM PASSWORD --}}
                        <div
                            x-data="{ showConfirmation: false }"
                        >

                            <label
                                for="password_confirmation"
                                class="mb-2 block text-sm font-black text-[#514043]"
                            >
                                Confirmar contraseña
                            </label>


                            <div class="relative">

                                <div
                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-[#b19795]"
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
                                            d="M9 12.75 11.25 15 15 10.5m1.5 0V6.75a4.5 4.5 0 0 0-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0 1 19.5 12.75v6A2.25 2.25 0 0 1 17.25 21H6.75A2.25 2.25 0 0 1 4.5 18.75v-6A2.25 2.25 0 0 1 6.75 10.5Z"
                                        />
                                    </svg>
                                </div>


                                <input
                                    id="password_confirmation"
                                    :type="showConfirmation ? 'text' : 'password'"
                                    name="password_confirmation"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Repite tu contraseña"
                                    class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] py-3.5 pl-12 pr-12 text-[#3d3032] placeholder:text-[#b7a3a0] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                                >


                                <button
                                    type="button"
                                    @click="showConfirmation = !showConfirmation"
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-[#9d8888] transition hover:text-[#ff6b57]"
                                >
                                    <svg
                                        x-show="!showConfirmation"
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
                                            d="M2.25 12s3.75-6 9.75-6 9.75 6 9.75 6-3.75 6-9.75 6-9.75-6-9.75-6Z"
                                        />
                                        <circle cx="12" cy="12" r="2.5"/>
                                    </svg>


                                    <svg
                                        x-show="showConfirmation"
                                        x-cloak
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
                                            d="M3 3l18 18M10.6 6.17A9.3 9.3 0 0 1 12 6c6 0 9.75 6 9.75 6a16.6 16.6 0 0 1-2.7 3.24M6.3 6.3C3.72 8 2.25 12 2.25 12S6 18 12 18c1.2 0 2.31-.24 3.3-.65"
                                        />
                                    </svg>
                                </button>

                            </div>


                            @error('password_confirmation')
                                <p class="mt-2 text-sm font-semibold text-[#c34d3c]">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- BOTÓN --}}
                        <button
                            type="submit"
                            class="mt-2 flex w-full items-center justify-center gap-2 rounded-[16px] bg-[#ff6b57] px-6 py-4 font-black text-white shadow-lg shadow-orange-100 transition hover:bg-[#ed5542]"
                        >
                            Crear mi cuenta

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
                        </button>

                    </form>


                    {{-- LOGIN --}}
                    <div
                        class="mt-6 border-t border-[#eee2da] pt-6 text-center"
                    >

                        <p
                            class="text-sm text-[#806d70]"
                        >
                            ¿Ya tienes una cuenta?

                            <a
                                href="{{ route('login') }}"
                                class="font-black text-[#ff604c] transition hover:text-[#db4937]"
                            >
                                Inicia sesión
                            </a>
                        </p>

                    </div>

                </div>

            </div>


            {{-- VOLVER MOBILE --}}
            <div
                class="mt-5 text-center lg:hidden"
            >
                <a
                    href="{{ route('home') }}"
                    class="text-sm font-bold text-[#796669]"
                >
                    ← Volver a VendeFácil
                </a>
            </div>

        </div>

    </div>

</section>

@endsection
