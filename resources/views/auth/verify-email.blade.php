@extends('layouts.public')

@section('title', 'Verifica tu correo - VendeFácil')

@section('description')
Verifica tu correo electrónico para activar tu cuenta de VendeFácil.
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
                        d="M21.75 6.75v10.5A2.25 2.25 0 0 1 19.5 19.5h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0-8.69 5.79a1.9 1.9 0 0 1-2.12 0L2.25 6.75"
                    />
                </svg>

                Revisa tu correo
            </div>


            <h1
                class="mt-6 max-w-xl text-[54px] font-black leading-[1] tracking-[-.055em] text-[#35272a]"
            >
                Ya casi está

                <span class="text-[#ff604c]">
                    todo listo.
                </span>
            </h1>


            <p
                class="mt-6 max-w-lg text-lg leading-8 text-[#756366]"
            >
                Antes de continuar, necesitamos confirmar que el correo
                que registraste realmente te pertenece.
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
                                d="m5 12 4 4L19 6"
                            />
                        </svg>
                    </div>

                    <div>

                        <h3 class="font-black text-[#403033]">
                            Solo necesitas un clic
                        </h3>

                        <p
                            class="mt-1 text-sm leading-6 text-[#806d70]"
                        >
                            Busca el mensaje de VendeFácil en tu bandeja
                            de entrada y presiona el enlace de verificación.
                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- PANEL --}}
        <div
            class="mx-auto w-full max-w-[540px]"
        >

            <div
                class="overflow-hidden rounded-[32px] border border-[#eaded5] bg-white shadow-[0_24px_60px_rgba(91,53,43,.12)]"
            >

                <div
                    class="h-[6px] bg-gradient-to-r from-[#ff7f6b] via-[#ffae78] to-[#ffd66b]"
                ></div>


                <div class="p-6 sm:p-8">

                    <a
                        href="{{ route('home') }}"
                        class="mb-6 inline-flex items-center gap-3 rounded-full border border-[#efe2d8] bg-[#fffaf4] px-3 py-2 text-sm font-black text-[#3d3032] transition hover:border-[#ffd2c8] hover:text-[#ff604c]"
                    >
                            <x-brand-logo size="panel" />
                    </a>


                    {{-- ICONO PRINCIPAL --}}
                    <div
                        class="flex h-16 w-16 items-center justify-center rounded-[22px] bg-[#fff0e4] text-[#ff6b57]"
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
                                d="M21.75 6.75v10.5A2.25 2.25 0 0 1 19.5 19.5h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0-8.69 5.79a1.9 1.9 0 0 1-2.12 0L2.25 6.75"
                            />
                        </svg>
                    </div>


                    <h2
                        class="mt-6 text-3xl font-black tracking-[-.04em] text-[#35272a]"
                    >
                        Verifica tu correo
                    </h2>


                    <p
                        class="mt-3 text-sm leading-7 text-[#806d70]"
                    >
                        Te enviamos un enlace de verificación al correo
                        que utilizaste al registrarte.
                    </p>


                    <p
                        class="mt-2 text-sm leading-7 text-[#806d70]"
                    >
                        Si no encuentras el mensaje, revisa tu carpeta de spam
                        o solicita uno nuevo.
                    </p>


                    <div
                        class="mt-5 rounded-[18px] border border-[#f1e4da] bg-[#fffaf4] px-4 py-3"
                    >
                        <p class="text-sm font-semibold leading-6 text-[#806d70]">
                            Mantén esta ventana abierta mientras revisas tu correo.
                        </p>
                    </div>


                    {{-- ESTADO --}}
                    @if (session('status') == 'verification-link-sent')

                        <div
                            class="mt-6 rounded-[18px] border border-[#bfe5d8] bg-[#edf8f4] px-4 py-3 text-sm font-semibold text-[#3d7764]"
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
                                        d="m5 12 4 4L19 6"
                                    />
                                </svg>

                                <span>
                                    Te enviamos un nuevo enlace de verificación.
                                </span>

                            </div>

                        </div>

                    @endif


                    {{-- ACCIONES --}}
                    <div
                        class="mt-7 space-y-3"
                    >

                        <form
                            method="POST"
                            action="{{ route('verification.send') }}"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="flex w-full items-center justify-center gap-2 rounded-[16px] bg-[#ff6b57] px-6 py-4 font-black text-white shadow-lg shadow-orange-100 transition hover:bg-[#ed5542]"
                            >
                                Reenviar correo de verificación

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
                                        d="M4.5 12a7.5 7.5 0 0 1 12.8-5.3L19.5 9M19.5 4.5V9h-4.5M19.5 12a7.5 7.5 0 0 1-12.8 5.3L4.5 15M4.5 19.5V15H9"
                                    />
                                </svg>
                            </button>

                        </form>


                        <form
                            method="POST"
                            action="{{ route('logout') }}"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="flex w-full items-center justify-center gap-2 rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-6 py-3.5 font-black text-[#6e5a5d] transition hover:bg-[#f8eee7]"
                            >
                                Cerrar sesión

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
                                        d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12"
                                    />
                                </svg>
                            </button>

                        </form>

                    </div>


                    <div
                        class="mt-6 border-t border-[#eee2da] pt-6"
                    >

                        <div
                            class="flex items-start gap-3 text-sm leading-6 text-[#8a7677]"
                        >

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.8"
                                stroke="currentColor"
                                class="mt-0.5 h-5 w-5 flex-shrink-0 text-[#b79532]"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 9v3.75m0 3h.008v.008H12v-.008ZM10.3 4.7 2.8 17.25A1.5 1.5 0 0 0 4.1 19.5h15.8a1.5 1.5 0 0 0 1.3-2.25L13.7 4.7a1.98 1.98 0 0 0-3.4 0Z"
                                />
                            </svg>


                            <span>
                                Si escribiste mal tu correo durante el registro,
                                cierra sesión y crea una nueva cuenta con la dirección correcta.
                            </span>

                        </div>

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
