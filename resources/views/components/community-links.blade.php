@auth
    <nav aria-label="Mi cuenta" class="border-b border-[#eaded5] bg-white px-4 py-3">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center gap-x-6 gap-y-3 text-sm font-bold text-[#5f4e51]">
            <a href="{{ route('favorites.index') }}" class="inline-flex items-center gap-2"><i data-lucide="heart" class="h-4 w-4 text-[#ed4e39]"></i>Favoritos</a>
            <a href="{{ route('notifications.index') }}" class="inline-flex items-center gap-2"><i data-lucide="bell" class="h-4 w-4 text-[#427764]"></i>Notificaciones @php($unread = auth()->user()->unreadNotifications()->count()) @if($unread)<span class="rounded-full bg-[#e7f5ef] px-2 text-[#315c4d]">{{ $unread }}</span>@endif</a>
            @if(auth()->user()->stores()->exists())<a href="{{ route('seller.analytics') }}" class="inline-flex items-center gap-2"><i data-lucide="chart-no-axes-combined" class="h-4 w-4"></i>Estadisticas</a>@endif
            @if(auth()->user()->is_admin)<a href="{{ route('admin.reports.index') }}" class="inline-flex items-center gap-2"><i data-lucide="flag" class="h-4 w-4"></i>Denuncias</a>@endif
        </div>
    </nav>
@endauth
