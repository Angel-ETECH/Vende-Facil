<x-app-layout>
    <x-workspace-page title="Mis favoritos">
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($favorites as $favorite)
                @php
                    $item = $favorite->product ?? $favorite->store;
                    $store = $favorite->product ? $favorite->product->store : $favorite->store;
                    $image = $favorite->product ? $item->image : $item->logo;
                @endphp
                <article class="overflow-hidden rounded-lg border border-[#eaded5] bg-white">
                    <a href="{{ route('stores.show', $store) }}{{ $favorite->product ? '#product-'.$item->id : '' }}" class="block">
                        @if($image)<img src="{{ asset('storage/'.$image) }}" alt="{{ $item->name }}" loading="lazy" decoding="async" class="h-48 w-full object-cover">@else<div class="flex h-48 items-center justify-center bg-[#e7f5ef]"><i data-lucide="store" class="h-12 w-12 text-[#427764]"></i></div>@endif
                        <div class="p-4"><p class="text-xs font-bold text-[#78676a]">{{ $favorite->product ? 'Producto' : 'Tienda' }} · {{ $store->city }}</p><h2 class="mt-2 break-words text-lg font-black">{{ $item->name }}</h2>@if($favorite->product)<p class="mt-2 font-bold text-[#ed4e39]">S/ {{ number_format($item->price, 2) }}</p>@endif</div>
                    </a>
                    <div class="flex justify-end border-t border-[#eaded5] p-3"><x-favorite-button :type="$favorite->product ? 'product' : 'store'" :id="$item->id" /></div>
                </article>
            @empty
                <p class="col-span-full py-12 text-center text-[#78676a]">Todavia no guardaste favoritos.</p>
            @endforelse
        </div>
        <div class="mt-6">{{ $favorites->links() }}</div>
    </x-workspace-page>
</x-app-layout>
