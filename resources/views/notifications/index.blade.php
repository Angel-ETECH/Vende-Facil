<x-app-layout>
    <x-workspace-page title="Notificaciones">
        <form method="POST" action="{{ route('notifications.read-all') }}" class="mb-6">@csrf @method('PATCH')<button class="rounded-lg border border-[#eaded5] bg-white px-4 py-2 font-bold">Marcar todas como leidas</button></form>
        <div class="divide-y divide-[#eaded5]">
            @forelse($notifications as $notice)
                <article class="py-5 {{ $notice->read_at ? '' : 'border-l-4 border-[#427764] pl-4' }}">
                    <div class="flex flex-wrap justify-between gap-3"><h2 class="font-black">{{ $notice->data['title'] }}</h2><time class="text-sm text-[#78676a]">{{ $notice->created_at->format('d/m/Y H:i') }}</time></div>
                    <p class="mt-2 break-words">{{ $notice->data['message'] }}</p>
                    <div class="mt-3 flex flex-wrap items-center gap-5">
                        <a href="{{ url($notice->data['path']) }}" class="font-bold text-[#427764]">Ver detalle</a>
                        @unless($notice->read_at)<form method="POST" action="{{ route('notifications.read', $notice->id) }}">@csrf @method('PATCH')<button class="text-sm font-bold text-[#78676a]">Marcar como leida</button></form>@endunless
                    </div>
                </article>
            @empty<p class="py-12 text-center text-[#78676a]">No tienes notificaciones.</p>@endforelse
        </div>
        <div class="mt-6">{{ $notifications->links() }}</div>
    </x-workspace-page>
</x-app-layout>
