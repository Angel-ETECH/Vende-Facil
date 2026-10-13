<x-app-layout>

    <div class="min-h-screen bg-[#fffaf5]">
        <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">

            @php
                $planUsagePercent = $store->unlimited_products
                    ? 100
                    : min(100, $productLimit > 0 ? round(($productCount / $productLimit) * 100) : 0);
            @endphp

            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-[#ffe3a3] px-4 py-2 text-xs font-black uppercase tracking-[.12em] text-[#72581f]">
                        Mi plan
                    </div>

                    <h1 class="mt-4 text-3xl font-black tracking-[-.04em] text-[#35272a] sm:text-4xl">
                        Plan de tu tienda
                    </h1>

                    <p class="mt-2 max-w-2xl text-base leading-7 text-[#806d70]">
                        Activa el plan para publicar más de {{ $productLimit }} productos en {{ $store->name }}.
                    </p>
                </div>

                <a
                    href="{{ route('products.index') }}"
                    class="inline-flex items-center justify-center rounded-full bg-[#442d35] px-5 py-3 text-sm font-black text-white transition hover:bg-[#ff6b57]"
                >
                    Volver al catálogo
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
                    Revisa los campos marcados antes de enviar tu comprobante.
                </div>
            @endif

            <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-[1.05fr_.95fr]">
                <div class="min-w-0 overflow-hidden rounded-[30px] border border-[#eaded5] bg-white shadow-[0_18px_45px_rgba(91,53,43,.08)]">
                    <div class="h-[6px] bg-gradient-to-r from-[#6a55ff] via-[#9c4dcc] to-[#ff6b57]"></div>

                    <div class="p-6 sm:p-8">
                        <p class="text-xs font-black uppercase tracking-[.12em] text-[#9b8583]">
                            Estado actual
                        </p>

                        <h2 class="mt-3 text-2xl font-black tracking-[-.035em] text-[#35272a]">
                            @if($store->unlimited_products)
                                Tu plan ilimitado está activo
                            @elseif($pendingPaymentProof)
                                Tienes un comprobante en revisión
                            @else
                                Activa cuando quieras, incluso antes de llegar al límite
                            @endif
                        </h2>

                        <p class="mt-3 text-sm leading-6 text-[#806d70]">
                            @if($store->unlimited_products)
                                Puedes publicar productos sin límite. Mantén tus productos actualizados para que tu tienda se vea viva.
                            @elseif($pendingPaymentProof)
                                Tu comprobante fue enviado y falta aprobación administrativa. Cuando se apruebe, tu tienda se desbloquea automáticamente.
                            @else
                                Has usado {{ $productCount }} de {{ $productLimit }} productos gratis. Te quedan {{ $remainingProductSlots }} publicaciones sin pago.
                            @endif
                        </p>

                        <div class="mt-6 grid gap-3 sm:grid-cols-3">
                            <div class="rounded-[20px] bg-[#fff8f1] px-4 py-4">
                                <p class="text-xs font-black uppercase tracking-[.08em] text-[#a08a89]">Usados</p>
                                <p class="mt-1 text-2xl font-black text-[#35272a]">{{ $productCount }}</p>
                            </div>

                            <div class="rounded-[20px] bg-[#fff8f1] px-4 py-4">
                                <p class="text-xs font-black uppercase tracking-[.08em] text-[#a08a89]">Gratis</p>
                                <p class="mt-1 text-2xl font-black text-[#35272a]">{{ $productLimit }}</p>
                            </div>

                            <div class="rounded-[20px] bg-[#e7f5ef] px-4 py-4">
                                <p class="text-xs font-black uppercase tracking-[.08em] text-[#427764]">Plan</p>
                                <p class="mt-1 text-2xl font-black text-[#315c4d]">S/ {{ $planPrice }}</p>
                            </div>
                        </div>

                        <div class="mt-6 rounded-[22px] border border-[#eaded5] bg-[#fffdf9] p-5">
                            <div class="flex items-center justify-between gap-3">
                                <p class="text-sm font-black text-[#35272a]">
                                    Uso del plan gratis
                                </p>

                                <span class="text-sm font-black text-[#ff604c]">
                                    {{ $store->unlimited_products ? 'Ilimitado' : $planUsagePercent . '%' }}
                                </span>
                            </div>

                            <div class="mt-3 h-3 overflow-hidden rounded-full bg-[#f1e4db]">
                                <div
                                    class="h-full rounded-full {{ $remainingProductSlots <= 2 && ! $store->unlimited_products ? 'bg-[#ff6b57]' : 'bg-[#98dbc6]' }}"
                                    style="width: {{ $planUsagePercent }}%"
                                ></div>
                            </div>

                            @if(! $store->unlimited_products && $remainingProductSlots <= 2)
                                <p class="mt-3 rounded-[16px] bg-[#fff3da] px-4 py-3 text-sm font-black text-[#806226]">
                                    Te quedan {{ $remainingProductSlots }} productos gratis. Puedes desbloquear ilimitado antes de llegar al límite.
                                </p>
                            @endif
                        </div>

                        <div class="mt-6 rounded-[22px] border border-[#eaded5] bg-[#fffdf9] p-5">
                            <p class="text-sm font-black text-[#35272a]">Paga con Yape o BCP</p>

                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                <div class="flex items-center gap-3 rounded-[18px] border border-[#eaded5] bg-white p-4">
                                    <img src="{{ asset('images/payments/yape.png') }}" alt="Yape" class="h-14 w-14 rounded-[16px] object-contain">
                                    <div>
                                        <p class="text-sm font-black text-[#35272a]">Yape</p>
                                        <p class="text-xs font-bold text-[#8d797a]">{{ $paymentNumber }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 rounded-[18px] border border-[#eaded5] bg-white p-4">
                                    <img src="{{ asset('images/payments/bcp.png') }}" alt="BCP" class="h-14 w-14 rounded-[16px] object-contain">
                                    <div>
                                        <p class="text-sm font-black text-[#35272a]">BCP</p>
                                        <p class="text-xs font-bold text-[#8d797a]">{{ $paymentNumber }}</p>
                                    </div>
                                </div>
                            </div>

                            <p class="mt-4 text-xs leading-5 text-[#806d70]">
                                Monto: S/ {{ $planPrice }}. Sube una captura o PDF del comprobante para que se pueda revisar.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="min-w-0 rounded-[30px] border border-[#eaded5] bg-white p-6 shadow-[0_18px_45px_rgba(91,53,43,.08)] sm:p-8">
                    @if($store->unlimited_products)
                        <div class="rounded-[24px] bg-[#e7f5ef] p-6">
                            <p class="text-xs font-black uppercase tracking-[.12em] text-[#427764]">Activo</p>
                            <h2 class="mt-3 text-2xl font-black tracking-[-.035em] text-[#315c4d]">
                                No necesitas subir más comprobantes
                            </h2>
                            <p class="mt-3 text-sm leading-6 text-[#427764]">
                                Tu tienda ya puede crecer sin límite de productos.
                            </p>
                        </div>
                    @elseif($pendingPaymentProof)
                        <div class="rounded-[24px] bg-[#fff3da] p-6">
                            <p class="text-xs font-black uppercase tracking-[.12em] text-[#806226]">En revisión</p>
                            <h2 class="mt-3 text-2xl font-black tracking-[-.035em] text-[#35272a]">
                                Tu comprobante ya fue enviado
                            </h2>
                            <p class="mt-3 text-sm leading-6 text-[#806d70]">
                                Espera la revisión administrativa. Si el comprobante está correcto, el plan se activará automáticamente.
                            </p>

                            <a
                                href="{{ route('payment-proofs.show', $pendingPaymentProof) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="mt-5 inline-flex w-full items-center justify-center rounded-[18px] bg-[#442d35] px-5 py-3.5 text-sm font-black text-white transition hover:bg-[#ff6b57]"
                            >
                                Ver comprobante enviado
                            </a>
                        </div>
                    @else
                        <h2 class="text-xl font-black tracking-[-.03em] text-[#35272a]">
                            Subir comprobante
                        </h2>

                        <form action="{{ route('seller.payments.store') }}" method="POST" enctype="multipart/form-data" class="mt-5 space-y-5">
                            @csrf

                            @php
                                $selectedPaymentMethod = in_array(old('method', 'yape'), ['yape', 'bcp'], true)
                                    ? old('method', 'yape')
                                    : 'yape';
                            @endphp

                            <div
                                x-data="{
                                    open: false,
                                    selected: @js($selectedPaymentMethod),
                                    methods: {
                                        yape: {
                                            label: 'Yape',
                                            image: @js(asset('images/payments/yape.png')),
                                            helper: 'Pago rápido por número',
                                        },
                                        bcp: {
                                            label: 'BCP',
                                            image: @js(asset('images/payments/bcp.png')),
                                            helper: 'Transferencia o depósito',
                                        },
                                    },
                                    get current() {
                                        return this.methods[this.selected] || this.methods.yape;
                                    },
                                    choose(method) {
                                        this.selected = method;
                                        this.open = false;
                                    },
                                }"
                                @click.outside="open = false"
                                class="relative"
                            >
                                <label id="method-label" class="block text-xs font-black uppercase tracking-[.1em] text-[#9b8583]">
                                    Método de pago
                                </label>

                                <input type="hidden" name="method" :value="selected">

                                <button
                                    type="button"
                                    aria-labelledby="method-label"
                                    :aria-expanded="open.toString()"
                                    @click="open = !open"
                                    class="mt-2 flex h-14 w-full items-center justify-between gap-3 rounded-[18px] border border-[#eaded5] bg-[#fffdf9] px-4 text-left text-sm font-black text-[#514043] shadow-sm transition hover:border-[#ffd2c8] hover:bg-white focus:border-[#ff8b78] focus:outline-none focus:ring-2 focus:ring-[#ff8b78]/25"
                                >
                                    <span class="flex min-w-0 items-center gap-3">
                                        <img
                                            :src="current.image"
                                            :alt="current.label"
                                            class="h-8 w-8 rounded-[10px] object-contain"
                                        >
                                        <span class="min-w-0">
                                            <span class="block truncate" x-text="current.label"></span>
                                            <span class="block truncate text-xs font-bold text-[#9b8583]" x-text="current.helper"></span>
                                        </span>
                                    </span>

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="2"
                                        stroke="currentColor"
                                        class="h-4 w-4 flex-shrink-0 text-[#7e6a6d] transition"
                                        :class="{ 'rotate-180': open }"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
                                    </svg>
                                </button>

                                <div
                                    x-show="open"
                                    x-cloak
                                    x-transition.origin.top
                                    class="absolute left-0 right-0 z-40 mt-2 overflow-hidden rounded-[20px] border border-[#eaded5] bg-white p-2 shadow-[0_18px_45px_rgba(91,53,43,.16)]"
                                >
                                    <button
                                        type="button"
                                        @click="choose('yape')"
                                        class="flex w-full items-center justify-between gap-3 rounded-[16px] px-3 py-3 text-left transition hover:bg-[#fff3e8]"
                                        :class="selected === 'yape' ? 'bg-[#ff6b57] text-white' : 'text-[#514043]'"
                                    >
                                        <span class="flex items-center gap-3">
                                            <img
                                                src="{{ asset('images/payments/yape.png') }}"
                                                alt="Yape"
                                                class="h-9 w-9 rounded-[12px] object-contain"
                                            >
                                            <span>
                                                <span class="block text-sm font-black">Yape</span>
                                                <span class="block text-xs font-bold opacity-80">Pago rápido por número</span>
                                            </span>
                                        </span>

                                        <span x-show="selected === 'yape'" class="text-sm font-black">✓</span>
                                    </button>

                                    <button
                                        type="button"
                                        @click="choose('bcp')"
                                        class="mt-1 flex w-full items-center justify-between gap-3 rounded-[16px] px-3 py-3 text-left transition hover:bg-[#fff3e8]"
                                        :class="selected === 'bcp' ? 'bg-[#ff6b57] text-white' : 'text-[#514043]'"
                                    >
                                        <span class="flex items-center gap-3">
                                            <img
                                                src="{{ asset('images/payments/bcp.png') }}"
                                                alt="BCP"
                                                class="h-9 w-9 rounded-[12px] object-contain"
                                            >
                                            <span>
                                                <span class="block text-sm font-black">BCP</span>
                                                <span class="block text-xs font-bold opacity-80">Transferencia o depósito</span>
                                            </span>
                                        </span>

                                        <span x-show="selected === 'bcp'" class="text-sm font-black">✓</span>
                                    </button>
                                </div>

                                @error('method')
                                    <p class="mt-2 text-xs font-bold text-[#b45040]">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="reference" class="block text-xs font-black uppercase tracking-[.1em] text-[#9b8583]">
                                    Operación o nota
                                </label>
                                <input
                                    id="reference"
                                    name="reference"
                                    type="text"
                                    value="{{ old('reference') }}"
                                    placeholder="Ejemplo: número de operación, hora del pago"
                                    class="mt-2 h-12 w-full rounded-[16px] border-[#eaded5] bg-[#fffdf9] text-sm font-bold text-[#514043] placeholder:text-[#b9a6a3] focus:border-[#ff8b78] focus:ring-[#ff8b78]/25"
                                >
                                @error('reference')
                                    <p class="mt-2 text-xs font-bold text-[#b45040]">{{ $message }}</p>
                                @enderror
                            </div>

                            <div x-data="{ proofName: '' }">
                                <label for="proof" class="block text-xs font-black uppercase tracking-[.1em] text-[#9b8583]">
                                    Comprobante
                                </label>
                                <label
                                    for="proof"
                                    class="mt-2 flex cursor-pointer items-center justify-between gap-3 rounded-[16px] border border-[#eaded5] bg-[#fffdf9] px-4 py-3 text-sm font-bold text-[#514043] shadow-sm transition hover:border-[#ff6a5c] hover:bg-white focus-within:border-[#ff6a5c] focus-within:ring-4 focus-within:ring-[#ff6a5c]/15"
                                >
                                    <span class="min-w-0 truncate" x-text="proofName || 'Seleccionar comprobante'"></span>
                                    <span class="shrink-0 rounded-full bg-[#442d35] px-4 py-2 text-xs font-black text-white">
                                        Elegir
                                    </span>
                                </label>
                                <input
                                    id="proof"
                                    name="proof"
                                    type="file"
                                    accept=".jpg,.jpeg,.png,.webp,.pdf,image/jpeg,image/png,image/webp,application/pdf"
                                    class="sr-only"
                                    @change="proofName = $event.target.files[0]?.name || ''"
                                    required
                                >
                                <p class="mt-2 text-xs leading-5 text-[#927e80]">
                                    JPG, PNG, WEBP o PDF. Máximo 4 MB.
                                </p>
                                @error('proof')
                                    <p class="mt-2 text-xs font-bold text-[#b45040]">{{ $message }}</p>
                                @enderror
                            </div>

                            <button
                                type="submit"
                                class="inline-flex w-full items-center justify-center rounded-[18px] bg-[#ff6b57] px-5 py-3.5 text-sm font-black text-white transition hover:bg-[#ed5542]"
                            >
                                Enviar comprobante
                            </button>
                        </form>

                        <a
                            href="{{ $paymentWhatsappUrl }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="mt-4 inline-flex w-full items-center justify-center rounded-[18px] bg-[#e6f5ef] px-5 py-3.5 text-sm font-black text-[#36745f] transition hover:bg-[#d6eee5]"
                        >
                            Consultar por WhatsApp
                        </a>
                    @endif
                </div>
            </div>

            <div class="mt-8 overflow-hidden rounded-[30px] border border-[#eaded5] bg-white">
                <div class="border-b border-[#f0e3da] px-6 py-5">
                    <h2 class="text-xl font-black tracking-[-.03em] text-[#35272a]">
                        Historial de comprobantes
                    </h2>
                </div>

                @if($paymentProofs->isEmpty())
                    <div class="px-6 py-12 text-center text-sm font-bold text-[#806d70]">
                        Todavía no enviaste comprobantes.
                    </div>
                @else
                    <div class="divide-y divide-[#f0e3da]">
                        @foreach($paymentProofs as $paymentProof)
                            <div class="grid gap-4 px-6 py-5 md:grid-cols-[1fr_auto] md:items-center">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="rounded-full px-3 py-1 text-xs font-black {{ $paymentProof->status === 'approved' ? 'bg-[#e7f5ef] text-[#427764]' : ($paymentProof->status === 'rejected' ? 'bg-[#fff0ec] text-[#b45040]' : 'bg-[#fff3da] text-[#806226]') }}">
                                            {{ $paymentProof->statusLabel() }}
                                        </span>
                                        <span class="text-xs font-bold uppercase tracking-[.08em] text-[#9b8583]">
                                            {{ strtoupper($paymentProof->method) }}
                                        </span>
                                    </div>

                                    <p class="mt-2 text-sm font-bold text-[#514043]">
                                        S/ {{ number_format($paymentProof->amount, 2) }} · {{ $paymentProof->created_at->format('d/m/Y H:i') }}
                                    </p>

                                    @if($paymentProof->reference)
                                        <p class="mt-1 text-xs text-[#927e80]">{{ $paymentProof->reference }}</p>
                                    @endif

                                    @if($paymentProof->admin_notes)
                                        <p class="mt-2 rounded-[14px] bg-[#fff8f1] px-3 py-2 text-xs font-bold text-[#806d70]">
                                            {{ $paymentProof->admin_notes }}
                                        </p>
                                    @endif
                                </div>

                                <a
                                    href="{{ route('payment-proofs.show', $paymentProof) }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center justify-center rounded-full bg-[#442d35] px-4 py-2 text-xs font-black text-white transition hover:bg-[#ff6b57]"
                                >
                                    Ver archivo
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

</x-app-layout>
