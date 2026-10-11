<x-app-layout>

    <div class="min-h-screen bg-[#fffaf5]">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-[#f1edff] px-4 py-2 text-xs font-black uppercase tracking-[.12em] text-[#5b49a8]">
                        Administración
                    </div>

                    <h1 class="mt-4 text-3xl font-black tracking-[-.04em] text-[#35272a] sm:text-4xl">
                        Pagos de planes
                    </h1>

                    <p class="mt-2 max-w-2xl text-base leading-7 text-[#806d70]">
                        Revisa comprobantes y activa productos ilimitados cuando el pago esté correcto.
                    </p>
                </div>

                <a
                    href="{{ route('admin.stores.index') }}"
                    class="inline-flex items-center justify-center rounded-full bg-[#442d35] px-5 py-3 text-sm font-black text-white transition hover:bg-[#ff6b57]"
                >
                    Ver tiendas
                </a>
            </div>

            @if(session('success'))
                <div class="mt-6 rounded-[18px] border border-[#bfe5d8] bg-[#edf8f4] px-4 py-3 text-sm font-semibold text-[#3d7764]">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mt-6 rounded-[18px] border border-[#f3c5bc] bg-[#fff0ec] px-4 py-3 text-sm font-semibold text-[#a24536]">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mt-6 rounded-[18px] border border-[#f3c5bc] bg-[#fff0ec] px-4 py-3 text-sm font-semibold text-[#a24536]">
                    Revisa la nota de rechazo antes de continuar.
                </div>
            @endif

            <div class="mt-8 grid gap-4 sm:grid-cols-3">
                <div class="rounded-[24px] border border-[#eaded5] bg-white p-5">
                    <p class="text-sm font-bold text-[#8e797b]">Pendientes</p>
                    <p class="mt-1 text-3xl font-black text-[#35272a]">{{ $pendingPaymentProofs }}</p>
                </div>

                <div class="rounded-[24px] border border-[#eaded5] bg-white p-5">
                    <p class="text-sm font-bold text-[#8e797b]">Aprobados</p>
                    <p class="mt-1 text-3xl font-black text-[#35272a]">{{ $approvedPaymentProofs }}</p>
                </div>

                <div class="rounded-[24px] border border-[#eaded5] bg-white p-5">
                    <p class="text-sm font-bold text-[#8e797b]">Rechazados</p>
                    <p class="mt-1 text-3xl font-black text-[#35272a]">{{ $rejectedPaymentProofs }}</p>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.payments.index') }}" class="mt-6 rounded-[24px] border border-[#eaded5] bg-white p-4">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    @php
                        $paymentStatusOptions = [
                            ['value' => 'pending', 'label' => 'Pendientes'],
                            ['value' => 'approved', 'label' => 'Aprobados'],
                            ['value' => 'rejected', 'label' => 'Rechazados'],
                            ['value' => 'todos', 'label' => 'Todos'],
                        ];
                    @endphp

                    <div
                        x-data="{
                            open: false,
                            selected: @js($status),
                            options: @js($paymentStatusOptions),
                            get label() {
                                return this.options.find((option) => option.value === this.selected)?.label || 'Pendientes';
                            },
                            choose(value) {
                                this.selected = value;
                                this.open = false;
                            },
                        }"
                        @click.outside="open = false"
                        class="relative block w-full sm:w-64"
                    >
                        <label class="mb-2 block text-xs font-black uppercase tracking-[.1em] text-[#9b8583]">
                            Estado
                        </label>

                        <input type="hidden" name="estado" :value="selected">

                        <button
                            type="button"
                            @click="open = !open"
                            class="flex h-12 w-full items-center justify-between gap-3 rounded-[16px] border border-[#eaded5] bg-[#fffdf9] px-4 text-left text-sm font-black text-[#514043] shadow-sm transition hover:border-[#ffd2c8] hover:bg-white focus:border-[#ff8b78] focus:outline-none focus:ring-2 focus:ring-[#ff8b78]/25"
                        >
                            <span x-text="label"></span>

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                                stroke="currentColor"
                                class="h-4 w-4 text-[#7e6a6d] transition"
                                :class="{ 'rotate-180': open }"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
                            </svg>
                        </button>

                        <div
                            x-show="open"
                            x-cloak
                            x-transition.origin.top
                            class="absolute left-0 right-0 z-40 mt-2 overflow-hidden rounded-[18px] border border-[#eaded5] bg-white p-2 shadow-[0_18px_45px_rgba(91,53,43,.16)]"
                        >
                            <template x-for="option in options" :key="option.value">
                                <button
                                    type="button"
                                    @click="choose(option.value)"
                                    class="flex w-full items-center justify-between rounded-[14px] px-4 py-3 text-left text-sm font-black transition hover:bg-[#fff3e8]"
                                    :class="selected === option.value ? 'bg-[#ff6b57] text-white' : 'text-[#4b3a3d]'"
                                >
                                    <span x-text="option.label"></span>
                                    <span x-show="selected === option.value">✓</span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="inline-flex h-12 items-center justify-center rounded-[16px] bg-[#442d35] px-5 text-sm font-black text-white transition hover:bg-[#ff6b57]"
                    >
                        Filtrar
                    </button>
                </div>
            </form>

            <div class="mt-6 overflow-hidden rounded-[30px] border border-[#eaded5] bg-white">
                @if($paymentProofs->isEmpty())
                    <div class="px-6 py-12 text-center text-sm font-bold text-[#806d70]">
                        No hay comprobantes en este estado.
                    </div>
                @else
                    <div class="divide-y divide-[#f0e3da]">
                        @foreach($paymentProofs as $paymentProof)
                            <article class="grid gap-5 px-6 py-6 xl:grid-cols-[1.15fr_.85fr]">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="rounded-full px-3 py-1 text-xs font-black {{ $paymentProof->status === 'approved' ? 'bg-[#e7f5ef] text-[#427764]' : ($paymentProof->status === 'rejected' ? 'bg-[#fff0ec] text-[#b45040]' : 'bg-[#fff3da] text-[#806226]') }}">
                                            {{ $paymentProof->statusLabel() }}
                                        </span>
                                        <span class="rounded-full bg-[#eef4ff] px-3 py-1 text-xs font-black text-[#496d9d]">
                                            {{ strtoupper($paymentProof->method) }}
                                        </span>
                                    </div>

                                    <h2 class="mt-3 text-xl font-black tracking-[-.03em] text-[#35272a]">
                                        {{ $paymentProof->store->name }}
                                    </h2>

                                    <p class="mt-1 text-sm font-bold text-[#806d70]">
                                        {{ $paymentProof->store->user->name }} · {{ $paymentProof->store->user->email }}
                                    </p>

                                    <div class="mt-4 grid gap-3 sm:grid-cols-3">
                                        <div class="rounded-[18px] bg-[#fff8f1] px-4 py-3">
                                            <p class="text-xs font-black uppercase tracking-[.08em] text-[#a08a89]">Monto</p>
                                            <p class="mt-1 font-black text-[#35272a]">S/ {{ number_format($paymentProof->amount, 2) }}</p>
                                        </div>

                                        <div class="rounded-[18px] bg-[#fff8f1] px-4 py-3">
                                            <p class="text-xs font-black uppercase tracking-[.08em] text-[#a08a89]">Enviado</p>
                                            <p class="mt-1 font-black text-[#35272a]">{{ $paymentProof->created_at->format('d/m H:i') }}</p>
                                        </div>

                                        <div class="rounded-[18px] bg-[#fff8f1] px-4 py-3">
                                            <p class="text-xs font-black uppercase tracking-[.08em] text-[#a08a89]">Número</p>
                                            <p class="mt-1 font-black text-[#35272a]">{{ $paymentProof->payment_number }}</p>
                                        </div>
                                    </div>

                                    @if($paymentProof->reference)
                                        <p class="mt-4 rounded-[18px] bg-[#fffdf9] px-4 py-3 text-sm font-bold text-[#806d70]">
                                            {{ $paymentProof->reference }}
                                        </p>
                                    @endif

                                    @if($paymentProof->reviewer)
                                        <p class="mt-3 text-xs font-bold text-[#927e80]">
                                            Revisado por {{ $paymentProof->reviewer->name }} el {{ $paymentProof->reviewed_at?->format('d/m/Y H:i') }}
                                        </p>
                                    @endif
                                </div>

                                <div class="rounded-[24px] border border-[#eaded5] bg-[#fffdf9] p-4">
                                    <div class="flex flex-wrap gap-2">
                                        <a
                                            href="{{ asset('storage/' . $paymentProof->proof_path) }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="inline-flex flex-1 items-center justify-center rounded-[16px] bg-[#442d35] px-4 py-3 text-sm font-black text-white transition hover:bg-[#ff6b57]"
                                        >
                                            Ver comprobante
                                        </a>

                                        <a
                                            href="{{ route('admin.stores.show', $paymentProof->store) }}"
                                            class="inline-flex flex-1 items-center justify-center rounded-[16px] bg-[#fff3da] px-4 py-3 text-sm font-black text-[#806226] transition hover:bg-[#ffe9ba]"
                                        >
                                            Ver tienda
                                        </a>
                                    </div>

                                    @if($paymentProof->isPending())
                                        <div class="mt-4 grid gap-3">
                                            <form action="{{ route('admin.payments.approve', $paymentProof) }}" method="POST">
                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="w-full rounded-[16px] bg-[#427764] px-4 py-3 text-sm font-black text-white transition hover:bg-[#315c4d]"
                                                >
                                                    Aprobar y activar ilimitados
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.payments.reject', $paymentProof) }}" method="POST" class="space-y-3">
                                                @csrf
                                                @method('PATCH')

                                                <textarea
                                                    name="admin_notes"
                                                    rows="2"
                                                    placeholder="Motivo opcional del rechazo"
                                                    class="w-full rounded-[16px] border-[#eaded5] bg-white text-sm font-bold text-[#514043] placeholder:text-[#b9a6a3] focus:border-[#ff8b78] focus:ring-[#ff8b78]/25"
                                                >{{ old('admin_notes') }}</textarea>

                                                <button
                                                    type="submit"
                                                    class="w-full rounded-[16px] bg-[#fff0ec] px-4 py-3 text-sm font-black text-[#b45040] transition hover:bg-[#ffe3dc]"
                                                >
                                                    Rechazar comprobante
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($paymentProof->admin_notes)
                                        <p class="mt-4 rounded-[16px] bg-[#fff8f1] px-4 py-3 text-sm font-bold text-[#806d70]">
                                            {{ $paymentProof->admin_notes }}
                                        </p>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>

                    @if($paymentProofs->hasPages())
                        <div class="border-t border-[#f0e3da] px-6 py-4">
                            {{ $paymentProofs->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

</x-app-layout>
