<x-app-layout>

    <div class="min-h-screen bg-[#fffaf5]">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-[#f1edff] px-4 py-2 text-xs font-black uppercase tracking-[.12em] text-[#5b49a8]">
                        Admin supremo
                    </div>

                    <h1 class="mt-4 text-3xl font-black tracking-[-.04em] text-[#35272a] sm:text-4xl">
                        Ganancias y control
                    </h1>

                    <p class="mt-2 max-w-2xl text-base leading-7 text-[#806d70]">
                        Revisa ingresos, comprobantes pendientes y el estado comercial de las tiendas.
                    </p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a
                        href="{{ route('admin.payments.index') }}"
                        class="inline-flex items-center justify-center rounded-full bg-[#442d35] px-5 py-3 text-sm font-black text-white transition hover:bg-[#ff6b57]"
                    >
                        Revisar pagos
                    </a>

                    <a
                        href="{{ route('admin.stores.index') }}"
                        class="inline-flex items-center justify-center rounded-full border border-[#e4d6cd] bg-white px-5 py-3 text-sm font-black text-[#6d595c] transition hover:bg-[#f8eee7]"
                    >
                        Ver tiendas
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mt-6 rounded-[18px] border border-[#bfe5d8] bg-[#edf8f4] px-4 py-3 text-sm font-semibold text-[#3d7764]">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mt-8 grid gap-4 md:grid-cols-3">
                <div class="rounded-[28px] border border-[#eaded5] bg-[#442d35] p-6 text-white shadow-[0_18px_45px_rgba(68,45,53,.18)]">
                    <p class="text-sm font-bold text-[#ead8de]">Total ganado</p>
                    <p class="mt-2 text-4xl font-black tracking-[-.04em]">S/ {{ number_format($totalRevenue, 2) }}</p>
                    <p class="mt-2 text-xs font-bold uppercase tracking-[.1em] text-[#f5c75e]">
                        {{ $approvedPaymentProofs }} pagos aprobados
                    </p>
                </div>

                <div class="rounded-[28px] border border-[#eaded5] bg-white p-6">
                    <p class="text-sm font-bold text-[#8e797b]">Ganado por Yape</p>
                    <p class="mt-2 text-3xl font-black tracking-[-.035em] text-[#35272a]">S/ {{ number_format($yapeRevenue, 2) }}</p>
                    <p class="mt-2 text-xs font-bold text-[#927e80]">Pagos móviles revisados</p>
                </div>

                <div class="rounded-[28px] border border-[#eaded5] bg-white p-6">
                    <p class="text-sm font-bold text-[#8e797b]">Ganado por BCP</p>
                    <p class="mt-2 text-3xl font-black tracking-[-.035em] text-[#35272a]">S/ {{ number_format($bcpRevenue, 2) }}</p>
                    <p class="mt-2 text-xs font-bold text-[#927e80]">Transferencias aprobadas</p>
                </div>
            </div>

            <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-8">
                @foreach([
                    ['label' => 'Pendientes', 'value' => $pendingPaymentProofs, 'class' => 'bg-[#fff3da] text-[#806226]'],
                    ['label' => 'Rechazados', 'value' => $rejectedPaymentProofs, 'class' => 'bg-[#fff0ec] text-[#b45040]'],
                    ['label' => 'Tiendas', 'value' => $totalStores, 'class' => 'bg-white text-[#35272a]'],
                    ['label' => 'Activas', 'value' => $activeStores, 'class' => 'bg-[#e7f5ef] text-[#427764]'],
                    ['label' => 'Ilimitadas', 'value' => $unlimitedStores, 'class' => 'bg-[#f1edff] text-[#5b49a8]'],
                    ['label' => 'Destacadas', 'value' => $featuredStores, 'class' => 'bg-[#eef4ff] text-[#496d9d]'],
                    ['label' => 'Verificadas', 'value' => $verifiedStores, 'class' => 'bg-[#fff8f1] text-[#a36c53]'],
                    ['label' => 'Productos', 'value' => $totalProducts, 'class' => 'bg-white text-[#35272a]'],
                ] as $stat)
                    <div class="rounded-[24px] border border-[#eaded5] p-5 {{ $stat['class'] }}">
                        <p class="text-sm font-bold opacity-80">{{ $stat['label'] }}</p>
                        <p class="mt-1 text-3xl font-black">{{ $stat['value'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 grid gap-6 lg:grid-cols-[1.05fr_.95fr]">
                <div class="overflow-hidden rounded-[30px] border border-[#eaded5] bg-white">
                    <div class="flex items-center justify-between border-b border-[#f0e3da] px-6 py-5">
                        <div>
                            <h2 class="text-xl font-black tracking-[-.03em] text-[#35272a]">
                                Pagos pendientes
                            </h2>
                            <p class="mt-1 text-sm text-[#806d70]">
                                Comprobantes que necesitan una decisión.
                            </p>
                        </div>
                    </div>

                    @if($pendingPayments->isEmpty())
                        <div class="px-6 py-12 text-center text-sm font-bold text-[#806d70]">
                            No hay comprobantes pendientes.
                        </div>
                    @else
                        <div class="divide-y divide-[#f0e3da]">
                            @foreach($pendingPayments as $payment)
                                <div class="grid gap-4 px-6 py-5 sm:grid-cols-[1fr_auto] sm:items-center">
                                    <div>
                                        <p class="font-black text-[#35272a]">{{ $payment->store->name }}</p>
                                        <p class="mt-1 text-sm font-bold text-[#806d70]">
                                            {{ strtoupper($payment->method) }} · S/ {{ number_format($payment->amount, 2) }} · {{ $payment->created_at->format('d/m H:i') }}
                                        </p>
                                    </div>

                                    <a
                                        href="{{ route('admin.payments.index', ['estado' => 'pending']) }}"
                                        class="inline-flex items-center justify-center rounded-full bg-[#ff6b57] px-4 py-2 text-xs font-black text-white transition hover:bg-[#ed5542]"
                                    >
                                        Revisar
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="overflow-hidden rounded-[30px] border border-[#eaded5] bg-white">
                    <div class="border-b border-[#f0e3da] px-6 py-5">
                        <h2 class="text-xl font-black tracking-[-.03em] text-[#35272a]">
                            Tiendas con plan ilimitado
                        </h2>
                        <p class="mt-1 text-sm text-[#806d70]">
                            Tiendas que ya desbloquearon más publicaciones.
                        </p>
                    </div>

                    @if($topUnlimitedStores->isEmpty())
                        <div class="px-6 py-12 text-center text-sm font-bold text-[#806d70]">
                            Aún no hay tiendas con plan ilimitado.
                        </div>
                    @else
                        <div class="divide-y divide-[#f0e3da]">
                            @foreach($topUnlimitedStores as $store)
                                <a
                                    href="{{ route('admin.stores.show', $store) }}"
                                    class="flex items-center justify-between gap-4 px-6 py-5 transition hover:bg-[#fffaf5]"
                                >
                                    <div class="min-w-0">
                                        <p class="truncate font-black text-[#35272a]">{{ $store->name }}</p>
                                        <p class="mt-1 text-sm font-bold text-[#806d70]">
                                            {{ $store->products_count }} productos
                                        </p>
                                    </div>

                                    <span class="rounded-full bg-[#f1edff] px-3 py-1 text-xs font-black text-[#5b49a8]">
                                        Ilimitado
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-6 overflow-hidden rounded-[30px] border border-[#eaded5] bg-white">
                <div class="border-b border-[#f0e3da] px-6 py-5">
                    <h2 class="text-xl font-black tracking-[-.03em] text-[#35272a]">
                        Historial de decisiones
                    </h2>
                    <p class="mt-1 text-sm text-[#806d70]">
                        Aprobaciones y rechazos realizados por administración.
                    </p>
                </div>

                @if($recentDecisions->isEmpty())
                    <div class="px-6 py-12 text-center text-sm font-bold text-[#806d70]">
                        Todavía no hay decisiones registradas.
                    </div>
                @else
                    <div class="divide-y divide-[#f0e3da]">
                        @foreach($recentDecisions as $decision)
                            <div class="grid gap-3 px-6 py-5 md:grid-cols-[1fr_auto] md:items-center">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="rounded-full px-3 py-1 text-xs font-black {{ $decision->status === 'approved' ? 'bg-[#e7f5ef] text-[#427764]' : 'bg-[#fff0ec] text-[#b45040]' }}">
                                            {{ $decision->statusLabel() }}
                                        </span>

                                        <span class="text-xs font-bold uppercase tracking-[.08em] text-[#9b8583]">
                                            {{ strtoupper($decision->method) }}
                                        </span>
                                    </div>

                                    <p class="mt-2 text-sm font-black text-[#35272a]">
                                        {{ $decision->store->name }} · S/ {{ number_format($decision->amount, 2) }}
                                    </p>

                                    <p class="mt-1 text-xs font-bold text-[#927e80]">
                                        {{ $decision->reviewer?->name ?: 'Admin' }} · {{ $decision->reviewed_at?->format('d/m/Y H:i') }}
                                    </p>
                                </div>

                                <a
                                    href="{{ route('admin.stores.show', $decision->store) }}"
                                    class="inline-flex items-center justify-center rounded-full bg-[#fff3da] px-4 py-2 text-xs font-black text-[#806226] transition hover:bg-[#ffe9ba]"
                                >
                                    Ver tienda
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>

</x-app-layout>
