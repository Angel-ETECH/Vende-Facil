@props(['type', 'id'])
@php($saved = in_array((int) $id, $type === 'store' ? ($savedStoreIds ?? []) : ($savedProductIds ?? []), true))
@auth
    <form method="POST" action="{{ route($saved ? 'favorites.destroy' : 'favorites.store', [$type, $id]) }}">
        @csrf
        @if($saved) @method('DELETE') @endif
        <button type="submit" title="{{ $saved ? 'Quitar de favoritos' : 'Guardar en favoritos' }}" aria-label="{{ $saved ? 'Quitar de favoritos' : 'Guardar en favoritos' }}" aria-pressed="{{ $saved ? 'true' : 'false' }}"
            class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-[#eaded5] bg-white text-[#ed4e39] shadow-sm transition hover:bg-[#fff0ec]">
            <i data-lucide="heart" class="h-5 w-5 {{ $saved ? 'fill-current' : '' }}"></i>
        </button>
    </form>
@else
    <a href="{{ route('login') }}" title="Inicia sesion para guardar favoritos" aria-label="Inicia sesion para guardar favoritos"
        class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-[#eaded5] bg-white text-[#ed4e39] shadow-sm"><i data-lucide="heart" class="h-5 w-5"></i></a>
@endauth
