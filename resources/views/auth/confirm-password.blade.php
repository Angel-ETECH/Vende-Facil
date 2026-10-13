@extends('layouts.public')

@section('title', 'Confirmar contraseña - VendeFácil')

@section('description')
Confirma tu contraseña para continuar de forma segura en VendeFácil.
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
                        d="M12 3.75 4.5 7.5v5.25c0 4.25 3 7.75 7.5 9 4.5-1.25 7.5-4.75 7.5-9V7.5L12 3.75Z"
                    />
                </svg>

                Área segura
            </div>


            <h1
                class="mt-6 max-w-xl text-[54px] font-black leading-[1] tracking-[-.055em] text-[#35272a]"
            >
                Solo falta

                <span class="text-[#ff604c]">
                    confirmar que eres tú.
                </span>
            </h1>


            <p
                class="mt-6 max-w-lg text-lg leading-8 text-[#756366]"
            >
                Por seguridad, necesitamos que confirmes tu contraseña
                antes de continuar con esta acción.
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
                                d="M9 12.75 11.25 15 15 10.5m1.5 0V6.75a4.5 4.5 0 0 0-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0 1 19.5 12.75v6A2.25 2.25 0 0 1 17.25 21H6.75A2.25 2.25 0 0 1 4.5 18.75v-6A2.25 2.25 0 0 1 6.75 10.5Z"
                            />
                        </svg>
                    </div>

                    <div>

                        <h3
                            class="font-black text-[#403033]"
                        >
                            Tu cuenta está protegida
                        </h3>

                        <p
                            class="mt-1 text-sm leading-6 text-[#806d70]"
                        >
                            Esta verificación ayuda a proteger acciones
                            importantes dentro de tu cuenta.
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
                            <x-brand-logo size="panel" />
                        </a>

                        <h2
                            class="text-3xl font-black tracking-[-.04em] text-[#35272a]"
                        >
                            Confirma tu contraseña
                        </h2>

                        <p
                            class="mt-2 text-sm leading-6 text-[#806d70]"
                        >
                            Ingresa tu contraseña actual para continuar.
                        </p>


                        <div
                            class="mt-5 rounded-[18px] border border-[#f1e4da] bg-[#fffaf4] px-4 py-3"
                        >
                            <p class="text-sm font-semibold leading-6 text-[#806d70]">
                                Esta confirmación protege cambios sensibles dentro de tu cuenta.
                            </p>
                        </div>

                    </div>


                    {{-- ERRORES --}}
                    @if($errors->any())

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
                                    La contraseña ingresada no es correcta.
                                </p>

                            </div>

                        </div>

                    @endif


                    <form
                        method="POST"
                        action="{{ route('password.confirm') }}"
                        class="mt-7"
                    >

                        @csrf


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

                                {{-- ICONO --}}
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
                                    autocomplete="current-password"
                                    placeholder="Tu contraseña actual"
                                    class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] py-3.5 pl-12 pr-12 text-[#3d3032] placeholder:text-[#b7a3a0] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                                >


                                {{-- MOSTRAR / OCULTAR --}}
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
                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="2.5"
                                        />
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
                            Confirmar y continuar

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

                        <a
                            href="{{ route('dashboard') }}"
                            class="inline-flex items-center gap-2 text-sm font-black text-[#ff604c] transition hover:text-[#db4937]"
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

                </div>

            </div>

        </div>

    </div>

</section>

@endsection
