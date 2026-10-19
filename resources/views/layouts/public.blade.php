@php
    $isNoIndexRoute = request()->routeIs([
        'login',
        'register',
        'password.*',
        'verification.*',
    ]);

    $seoTitle = trim($__env->yieldContent('title', config('seo.title')));
    $seoDescription = trim($__env->yieldContent('description', config('seo.description')));
    $seoCanonical = trim($__env->yieldContent('canonical', url()->current()));
    $seoImage = trim($__env->yieldContent('image', asset(config('seo.image'))));
    $seoType = trim($__env->yieldContent('type', 'website'));
    $seoRobots = trim($__env->yieldContent(
        'robots',
        $isNoIndexRoute
            ? 'noindex, nofollow'
            : 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1'
    ));

    $baseStructuredData = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Organization',
                '@id' => url('/').'#organization',
                'name' => config('seo.site_name'),
                'url' => url('/'),
                'logo' => asset('images/brand/vendefacil-logo.png'),
                'sameAs' => [
                    'https://github.com/Angel-ETECH/Vende-Facil',
                ],
            ],
            [
                '@type' => 'WebSite',
                '@id' => url('/').'#website',
                'url' => url('/'),
                'name' => config('seo.site_name'),
                'description' => config('seo.description'),
                'publisher' => [
                    '@id' => url('/').'#organization',
                ],
                'inLanguage' => 'es-PE',
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => route('home').'?buscar={search_term_string}',
                    'query-input' => 'required name=search_term_string',
                ],
            ],
        ],
    ];
@endphp

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta name="description" content="{{ $seoDescription }}">
    <meta name="robots" content="{{ $seoRobots }}">
    <meta name="author" content="Angel ETECH">
    <meta name="theme-color" content="#ff6b57">

    <title>
        {{ $seoTitle }}
    </title>

    <link rel="canonical" href="{{ $seoCanonical }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon-vf.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon-vf.png') }}">

    <meta property="og:site_name" content="{{ config('seo.site_name') }}">
    <meta property="og:locale" content="{{ config('seo.locale') }}">
    <meta property="og:type" content="{{ $seoType }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $seoCanonical }}">
    <meta property="og:image" content="{{ $seoImage }}">
    <meta property="og:image:alt" content="{{ $seoTitle }}">

    <meta name="twitter:card" content="{{ config('seo.twitter_card') }}">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:image" content="{{ $seoImage }}">

    <script type="application/ld+json">
        {!! json_encode($baseStructuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_PRETTY_PRINT) !!}
    </script>

    @stack('structured-data')
    @stack('head')

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <style>

        :root {
            --cream: #fffaf2;
            --paper: #fffdf8;

            --ink: #2d1f22;
            --muted: #78676a;

            --coral: #ff6b57;
            --coral-dark: #ed4e39;

            --yellow: #ffd66b;
            --mint: #98dbc6;
            --pink: #ffb8c8;
            --peach: #ffb38a;

            --plum: #5c3047;
            --plum-dark: #3e2030;

            --green: #25d366;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;

            font-family:
                "Segoe UI",
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                sans-serif;

            background:
                radial-gradient(
                    circle at 10% 8%,
                    rgba(255, 214, 107, .18),
                    transparent 25rem
                ),
                radial-gradient(
                    circle at 95% 20%,
                    rgba(255, 184, 200, .17),
                    transparent 28rem
                ),
                var(--cream);

            color: var(--ink);
        }

        [x-cloak] {
            display: none !important;
        }

        .vf-container {
            width: min(1180px, calc(100% - 32px));
            margin-inline: auto;
        }

        .vf-navbar {
            background: rgba(255, 250, 242, .90);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }

        .vf-shadow {
            box-shadow:
                0 18px 50px rgba(98, 55, 44, .10);
        }

        .vf-card {
            transition:
                transform .28s ease,
                box-shadow .28s ease;
        }

        .vf-card:hover {
            transform: translateY(-7px) rotate(-.2deg);

            box-shadow:
                0 24px 55px rgba(98, 55, 44, .13);
        }

        .vf-product-image img {
            transition: transform .55s ease;
        }

        .vf-product-image:hover img {
            transform: scale(1.06);
        }

        .vf-wiggle {
            animation: vfFloat 5s ease-in-out infinite;
        }

        @keyframes vfFloat {
            0%, 100% {
                transform: translateY(0px) rotate(-2deg);
            }

            50% {
                transform: translateY(-10px) rotate(1deg);
            }
        }

        .vf-marquee {
            overflow: hidden;
            white-space: nowrap;
        }

        .vf-marquee-track {
            display: inline-flex;
            min-width: max-content;
            animation: vfMarquee 23s linear infinite;
        }

        @keyframes vfMarquee {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-50%);
            }
        }

        .vf-blob {
            border-radius:
                43% 57% 63% 37% /
                45% 38% 62% 55%;
        }

        .vf-doodle {
            position: absolute;
            pointer-events: none;
            user-select: none;
        }

        .vf-section-title {
            font-size: clamp(2rem, 4vw, 3.7rem);
            line-height: 1;
            letter-spacing: -.04em;
            font-weight: 900;
        }

        .vf-shop-cover-1 {
            background:
                radial-gradient(circle at 75% 35%, #ffd66b 0 15%, transparent 16%),
                linear-gradient(135deg, #ff7a65, #ffaf83);
        }

        .vf-shop-cover-2 {
            background:
                radial-gradient(circle at 75% 35%, #ffb8c8 0 15%, transparent 16%),
                linear-gradient(135deg, #6fcdb3, #b4ead9);
        }

        .vf-shop-cover-3 {
            background:
                radial-gradient(circle at 75% 35%, #ffd66b 0 15%, transparent 16%),
                linear-gradient(135deg, #ab6685, #eaa3bc);
        }

        .vf-shop-cover-4 {
            background:
                radial-gradient(circle at 75% 35%, #98dbc6 0 15%, transparent 16%),
                linear-gradient(135deg, #ff9f68, #ffd084);
        }

        .vf-search {
            box-shadow:
                0 18px 45px rgba(94, 56, 45, .13);
        }

        @media (max-width: 640px) {

            .vf-container {
                width: min(100% - 24px, 1180px);
            }

            .vf-card:hover {
                transform: translateY(-3px);
            }
        }

    </style>

</head>


<body>

    {{-- NAVBAR --}}
    <header
        x-data="{ mobileOpen: false }"
        class="vf-navbar sticky top-0 z-50 border-b border-[#eadfd3]"
    >

        <div class="vf-container">

            <div class="flex h-[76px] items-center justify-between">

                <a
                    href="{{ route('home') }}"
                    class="flex items-center gap-3"
                >
                    <x-brand-logo />

                </a>


                <nav class="hidden items-center gap-7 lg:flex">

                    <a
                        href="{{ route('home') }}"
                        class="font-bold text-[#5f4e51] transition hover:text-[#ff6b57]"
                    >
                        Inicio
                    </a>

                    <a
                        href="{{ route('home') }}#productos"
                        class="font-bold text-[#5f4e51] transition hover:text-[#ff6b57]"
                    >
                        Productos
                    </a>

                    <a
                        href="{{ route('home') }}#tiendas"
                        class="font-bold text-[#5f4e51] transition hover:text-[#ff6b57]"
                    >
                        Tiendas
                    </a>

                    <a
                        href="{{ route('home') }}#como-funciona"
                        class="font-bold text-[#5f4e51] transition hover:text-[#ff6b57]"
                    >
                        Cómo funciona
                    </a>

                </nav>


                <div class="hidden items-center gap-2 sm:flex">

                    @auth

                        <a
                            href="{{ route('dashboard') }}"
                            class="rounded-full px-4 py-2.5 text-sm font-bold text-[#5f4e51] transition hover:bg-[#f6eadf]"
                        >
                            Mi panel
                        </a>

                        <a
                            href="{{ route('dashboard') }}"
                            class="rounded-full bg-[#3e2030] px-5 py-3 text-sm font-black text-white transition hover:bg-[#ff6b57]"
                        >
                            Administrar tienda
                        </a>

                    @else

                        <a
                            href="{{ route('login') }}"
                            class="rounded-full px-4 py-2.5 text-sm font-bold text-[#5f4e51] transition hover:bg-[#f6eadf]"
                        >
                            Entrar
                        </a>

                        <a
                            href="{{ route('register') }}"
                            class="rounded-full bg-[#3e2030] px-5 py-3 text-sm font-black text-white transition hover:bg-[#ff6b57]"
                        >
                            Abrir mi tienda
                        </a>

                    @endauth

                </div>


                <button
                    type="button"
                    @click="mobileOpen = !mobileOpen"
                    class="flex h-11 w-11 items-center justify-center rounded-full bg-[#f4e8dd] text-[#3e2030] sm:hidden"
                >

                    <svg
                        x-show="!mobileOpen"
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
                        x-show="mobileOpen"
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
                            d="M6 18 18 6M6 6l12 12"
                        />
                    </svg>

                </button>

            </div>


            <div
                x-show="mobileOpen"
                x-transition
                x-cloak
                class="border-t border-[#eadfd3] pb-5 pt-3 sm:hidden"
            >

                <a
                    href="{{ route('home') }}"
                    class="block rounded-2xl px-4 py-3 font-bold"
                >
                    Inicio
                </a>

                <a
                    href="{{ route('home') }}#productos"
                    class="block rounded-2xl px-4 py-3 font-bold"
                >
                    Productos
                </a>

                <a
                    href="{{ route('home') }}#tiendas"
                    class="block rounded-2xl px-4 py-3 font-bold"
                >
                    Tiendas
                </a>

                @auth

                    <a
                        href="{{ route('dashboard') }}"
                        class="mt-2 block rounded-full bg-[#3e2030] px-5 py-3 text-center font-black text-white"
                    >
                        Mi panel
                    </a>

                @else

                    <a
                        href="{{ route('login') }}"
                        class="mt-2 block rounded-full border border-[#d8c6b8] px-5 py-3 text-center font-bold"
                    >
                        Iniciar sesión
                    </a>

                    <a
                        href="{{ route('register') }}"
                        class="mt-2 block rounded-full bg-[#ff6b57] px-5 py-3 text-center font-black text-white"
                    >
                        Crear mi tienda
                    </a>

                @endauth

            </div>

        </div>

    </header>


    <main>
        <x-community-links />
        @if(session('success'))<div role="status" class="vf-container py-4 font-bold text-[#427764]">{{ session('success') }}</div>@endif
        @yield('content')
    </main>


    <footer
        class="relative overflow-hidden bg-[#3e2030] text-white"
    >

        <div class="vf-container relative py-14">

            <div class="grid gap-10 md:grid-cols-2">

                <div>

                    <div class="text-3xl font-black">
                        Vende<span class="text-[#ff927d]">Fácil</span>
                    </div>

                    <p class="mt-4 max-w-xl leading-7 text-[#d8c2ca]">
                        Un rincón digital donde los negocios muestran
                        lo suyo y las personas encuentran cosas lindas
                        sin tantas vueltas.
                    </p>

                </div>


                <nav class="flex flex-wrap items-start gap-x-6 gap-y-3 text-sm font-bold text-[#ead8de] md:justify-end" aria-label="Navegación del pie de página">
                    <a href="{{ route('home') }}" class="hover:text-white">Inicio</a>
                    <a href="{{ route('home') }}#productos" class="hover:text-white">Productos</a>
                    <a href="{{ route('home') }}#tiendas" class="hover:text-white">Tiendas</a>
                    <a href="{{ route('home') }}#como-funciona" class="hover:text-white">Cómo funciona</a>
                </nav>

            </div>


            <div
                class="mt-12 border-t border-white/10 pt-6 text-sm text-[#c8adb8]"
            >
                © {{ date('Y') }} VendeFácil · Hecho para negocios con personalidad.
            </div>

        </div>

    </footer>

</body>
</html>
