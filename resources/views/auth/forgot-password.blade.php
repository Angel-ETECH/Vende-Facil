@extends('layouts.public')

@section('title', 'Recuperar contraseña - VendeFácil')

@section('description')
Recupera el acceso a tu cuenta de VendeFácil.
@endsection


@section('content')

<section class="relative min-h-[calc(100vh-74px)] overflow-hidden bg-[#fff3e5]">

    {{-- DECORACIÓN --}}
    <div
        class="absolute -left-24 top-12 h-72 w-72 rounded-full bg-[#ffd66b]/30"
    ></div>

    <div
        class="absolute -right-24 top-16 h-80 w-80 rounded-full bg-[#ffb8c8]/40"
    ></div>

    <div
        class="absolute bottom-10 left-[42%] h-36 w-36 rounded-full bg-[#98dbc6]/20 blur-xl"
    ></div>


    <div
        class="vf-container relative grid min-h-[calc(100vh-74px)] items-center gap-12 py-12 lg:grid-cols-[.9fr_1.1fr]"
    >

        {{-- IZQUIERDA --}}
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
                        d="M12 6v6l3 2"
                    />
                    <circle
                        cx="12"
                        cy="12"
                        r="9"
                    />
                </svg>

                Recupera tu acceso
            </div>


            <h1
                class="mt-6 max-w-xl text-[54px] font-black leading-[1] tracking-[-.055em] text-[#35272a]"
            >
                Volver a tu cuenta

                <span class="text-[#ff604c]">
                    es muy sencillo.
                </span>
            </h1>


            <p
                class="mt-6 max-w-lg text-lg leading-8 text-[#756366]"
            >
                Escribe el correo con el que te registraste y te enviaremos
                un enlace para crear una nueva contraseña.
            </p>


            <div
                class="mt-10 rounded-[26px] border border-[#eaded5] bg-white/70 p-6 backdrop-blur-sm"
            >

                <div class="flex items-start gap-4">

                    <div
                        class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-2xl bg-[#e8f5f0] text-[#4e8b79]"
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
                                d="M21.75 6.75v10.5A2.25 2.25 0 0 1 19.5 19.5h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0-8.69 5.79a1.9 1.9 0 0 1-2.12 0L2.25 6.75"
                            />
                        </svg>
                    </div>

                    <div>

                        <h3
                            class="font-black text-[#403033]"
                        >
                            Te enviaremos un enlace seguro
                        </h3>

                        <p
                            class="mt-1 text-sm leading-6 text-[#806d70]"
                        >
                            Revisa también tu carpeta de spam si el correo
                            no aparece después de unos minutos.
                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- FORMULARIO --}}
        <div
            class="mx-auto w-full max-w-[520px]"
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
                            Recuperar contraseña
                        </h2>

                        <p
                            class="mt-2 text-sm leading-6 text-[#806d70]"
                        >
                            Ingresa tu correo electrónico para recibir
                            el enlace de recuperación.
                        </p>


                        <div
                            class="mt-5 rounded-[18px] border border-[#f1e4da] bg-[#fffaf4] px-4 py-3"
                        >
                            <p class="text-sm font-semibold leading-6 text-[#806d70]">
                                Usa el mismo correo con el que administras tu tienda en VendeFácil.
                            </p>
                        </div>

                    </div>


                    {{-- STATUS --}}
                    @if (session('status'))

                        <div
                            class="mt-6 rounded-[18px] border border-[#bfe5d8] bg-[#edf8f4] px-4 py-3 text-sm font-semibold text-[#3d7764]"
                        >
                            <div
                                class="flex items-start gap-3"
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

                                <span>
                                    {{ session('status') }}
                                </span>

                            </div>
                        </div>

                    @endif


                    {{-- ERRORES --}}
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

                                <p class="font-black">
                                    Revisa el correo ingresado.
                                </p>

                            </div>

                        </div>

                    @endif


                    <form
                        method="POST"
                        action="{{ route('password.email') }}"
                        class="mt-7"
                    >

                        @csrf


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
                                    autofocus
                                    autocomplete="email"
                                    placeholder="correo@ejemplo.com"
                                    class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] py-3.5 pl-12 pr-4 text-[#3d3032] placeholder:text-[#b7a3a0] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                                >

                            </div>


                            @error('email')

                                <p
                                    class="mt-2 text-sm font-semibold text-[#c34d3c]"
                                >
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- BOTÓN --}}
                        <button
                            type="submit"
                            class="mt-6 flex w-full items-center justify-center gap-2 rounded-[16px] bg-[#ff6b57] px-6 py-4 font-black text-white shadow-lg shadow-orange-100 transition hover:bg-[#ed5542]"
                        >

                            Enviar enlace de recuperación

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


                    {{-- VOLVER --}}
                    <div
                        class="mt-6 border-t border-[#eee2da] pt-6 text-center"
                    >

                        <p
                            class="text-sm text-[#806d70]"
                        >
                            ¿Recordaste tu contraseña?

                            <a
                                href="{{ route('login') }}"
                                class="font-black text-[#ff604c] transition hover:text-[#db4937]"
                            >
                                Iniciar sesión
                            </a>
                        </p>

                    </div>

                </div>

            </div>


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
