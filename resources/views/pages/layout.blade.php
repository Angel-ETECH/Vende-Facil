@extends('layouts.public')

@section('content')
<section class="bg-[#fffaf8] py-12 sm:py-16">
    <div class="vf-container max-w-[960px]">
        <header class="max-w-[760px]">
            <p class="text-sm font-bold text-[#b94332]">VendeFácil · Negocios con onda</p>
            <h1 class="mt-4 text-3xl font-black leading-tight text-[#35272a] sm:text-4xl">@yield('page-heading')</h1>
            <p class="mt-5 text-base leading-8 text-[#746164]">@yield('page-intro')</p>
        </header>
        <nav class="mt-8 flex flex-wrap gap-x-6 gap-y-3 border-y border-[#eddfd9] py-5 text-sm font-bold" aria-label="Información de VendeFácil">
            @foreach(['help' => 'Ayuda', 'terms' => 'Condiciones de uso', 'privacy' => 'Privacidad'] as $name => $label)
                <a href="{{ route($name) }}" @if(request()->routeIs($name)) aria-current="page" @endif class="{{ request()->routeIs($name) ? 'text-[#b94332]' : 'text-[#746164]' }} hover:underline">{{ $label }}</a>
            @endforeach
        </nav>
        <article class="vf-info-content mt-10 max-w-[760px] space-y-9 leading-8 text-[#69565a]">
            @yield('info-content')
        </article>
        <div class="mt-12 border-t border-[#eddfd9] pt-8">
            <h2 class="text-xl font-black text-[#35272a]">¿Necesitas ayuda con tu cuenta o un pago?</h2>
            <p class="mt-3 leading-7 text-[#746164]">Escribe a la administración de VendeFácil. Indica tu tienda y el motivo de tu consulta. Nunca envíes tu contraseña ni códigos de acceso.</p>
            <a href="https://wa.me/{{ preg_replace('/\D/', '', (string) config('vendefacil.support_whatsapp')) }}" target="_blank" rel="noopener noreferrer" class="mt-5 inline-flex items-center gap-3 rounded-full bg-[#3e2030] px-6 py-3 font-bold text-white hover:bg-[#b94332]">Contactar por WhatsApp <i data-lucide="arrow-up-right" class="h-4 w-4" aria-hidden="true"></i></a>
            @if(config('vendefacil.support_email'))
                <p class="mt-4 text-sm"><a class="text-[#b94332] underline" href="mailto:{{ config('vendefacil.support_email') }}">{{ config('vendefacil.support_email') }}</a></p>
            @endif
        </div>
    </div>
</section>
@endsection
