<x-app-layout>
    <x-workspace-page title="Denuncias">
        <nav aria-label="Estado de denuncias" class="mb-6 flex flex-wrap gap-2">
            @foreach(['pending' => 'Pendientes', 'resolved' => 'Resueltas', 'dismissed' => 'Descartadas'] as $value => $label)
                <a href="{{ route('admin.reports.index', ['estado' => $value]) }}" @if($status === $value) aria-current="page" @endif class="rounded-lg border border-[#eaded5] px-4 py-2 font-bold {{ $status === $value ? 'bg-[#442d35] text-white' : 'bg-white' }}">{{ $label }}</a>
            @endforeach
        </nav>
        <div class="space-y-5">
            @forelse($reports as $report)
                <article class="rounded-lg border border-[#eaded5] bg-white p-5">
                    <div class="flex flex-wrap justify-between gap-3"><h2 class="break-words font-black">{{ $report->product?->name ?? $report->store->name }}</h2><time class="text-sm text-[#78676a]">{{ $report->created_at->format('d/m/Y H:i') }}</time></div>
                    <p class="mt-2 text-sm text-[#78676a]">{{ $report->user->name }} · {{ ['fraud' => 'Posible estafa', 'prohibited' => 'Contenido prohibido', 'misleading' => 'Información engañosa', 'other' => 'Otro motivo'][$report->reason] ?? $report->reason }}</p>
                    <p class="mt-4 whitespace-pre-line break-words">{{ $report->details }}</p>
                    <a href="{{ route('admin.stores.show', $report->store) }}" class="mt-3 inline-block font-bold text-[#427764]">Revisar tienda</a>
                    @if($report->status === 'pending')
                        <form action="{{ route('admin.reports.review', $report) }}" method="POST" class="mt-5 space-y-4 border-t border-[#eaded5] pt-5">
                            @csrf @method('PATCH')
                            <fieldset class="flex flex-wrap gap-5"><legend class="mb-2 font-bold">Decision</legend><label class="flex items-center gap-2"><input type="radio" name="status" value="resolved" checked class="text-[#427764]">Resolver</label><label class="flex items-center gap-2"><input type="radio" name="status" value="dismissed" class="text-[#427764]">Descartar</label></fieldset>
                            <label class="flex items-center gap-2"><input type="checkbox" name="hide_listing" value="1" class="rounded border-[#eaded5] text-[#ed4e39]">Ocultar la publicacion al resolver</label>
                            <label class="block"><span class="font-bold">Motivo de la decision</span><textarea required name="admin_notes" maxlength="1000" rows="2" class="mt-2 w-full rounded-lg border-[#eaded5]">{{ old('admin_notes') }}</textarea></label>
                            <button class="rounded-lg bg-[#442d35] px-5 py-3 font-bold text-white">Guardar decision</button>
                        </form>
                    @else
                        <p class="mt-4 break-words text-sm">{{ $report->admin_notes }}</p><p class="mt-2 text-xs text-[#78676a]">{{ $report->reviewer?->name }} · {{ $report->reviewed_at?->format('d/m/Y H:i') }}</p>
                    @endif
                </article>
            @empty<p class="py-12 text-center text-[#78676a]">No hay denuncias en este estado.</p>@endforelse
        </div>
        <div class="mt-6">{{ $reports->links() }}</div>
    </x-workspace-page>
</x-app-layout>
