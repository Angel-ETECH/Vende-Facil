@php
    $pageTitle = match (true) {
        request()->routeIs('dashboard') => 'Mi panel - VendeFácil',
        request()->routeIs('admin.stores.index') => 'Admin tiendas - VendeFácil',
        request()->routeIs('admin.stores.show') => 'Detalle de tienda - VendeFácil',
        request()->routeIs('products.index') => 'Mis productos - VendeFácil',
        request()->routeIs('products.create') => 'Nuevo producto - VendeFácil',
        request()->routeIs('products.edit') => 'Editar producto - VendeFácil',
        request()->routeIs('stores.create') => 'Crear tienda - VendeFácil',
        request()->routeIs('stores.edit') => 'Editar tienda - VendeFácil',
        request()->routeIs('profile.edit') => 'Mi perfil - VendeFácil',
        default => config('app.name', 'VendeFácil'),
    };
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $pageTitle }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        <link rel="shortcut icon" href="{{ asset('favicon.svg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
