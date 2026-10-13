<x-app-layout>
    <x-workspace-page title="Denunciar publicación">
        <p class="mb-6 break-words font-bold">{{ $product?->name ?? $store->name }}</p>
        <form action="{{ route('reports.store', $store) }}" method="POST" class="max-w-2xl space-y-6">
            @csrf
            @if($product)<input type="hidden" name="product_id" value="{{ $product->id }}">@endif
            <fieldset><legend class="mb-3 font-bold">Motivo</legend>
                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach(['fraud' => 'Posible estafa', 'prohibited' => 'Contenido prohibido', 'misleading' => 'Información engañosa', 'other' => 'Otro motivo'] as $value => $label)
                        <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-[#eaded5] bg-white p-4"><input type="radio" name="reason" value="{{ $value }}" @checked(old('reason', 'fraud') === $value) required class="border-[#eaded5] text-[#ed4e39] focus:ring-[#ed4e39]"><span>{{ $label }}</span></label>
                    @endforeach
                </div>
            </fieldset>
            <label class="block"><span class="font-bold">¿Qué ocurrió?</span><textarea name="details" required minlength="10" maxlength="2000" rows="5" class="mt-2 w-full rounded-lg border-[#eaded5] focus:border-[#ed4e39] focus:ring-[#ed4e39]">{{ old('details') }}</textarea></label>
            <div class="flex flex-wrap gap-4"><button class="rounded-lg bg-[#442d35] px-5 py-3 font-bold text-white">Enviar denuncia</button><a href="{{ route('stores.show', $store) }}" class="px-5 py-3 font-bold">Cancelar</a></div>
        </form>
    </x-workspace-page>
</x-app-layout>
