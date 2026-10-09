<div
    class="overflow-hidden rounded-[30px] border border-[#eaded5] bg-white shadow-[0_18px_45px_rgba(91,53,43,.08)]"
>
    <div class="h-[6px] bg-gradient-to-r from-[#6a55ff] via-[#9c4dcc] to-[#ff6b57]"></div>

    <div class="grid gap-6 p-6 lg:grid-cols-[1.05fr_.95fr] lg:p-8">
        <div>
            <span class="inline-flex rounded-full bg-[#fff3da] px-4 py-1.5 text-xs font-black uppercase tracking-[.12em] text-[#806226]">
                {{ $remainingProductSlots === 0 ? 'Límite gratuito alcanzado' : 'Mejora disponible' }}
            </span>

            <h2 class="mt-4 text-2xl font-black tracking-[-.035em] text-[#35272a] sm:text-3xl">
                @if($remainingProductSlots === 0)
                    Tu tienda ya usó sus {{ $productLimit }} productos gratis
                @else
                    Activa productos ilimitados cuando quieras
                @endif
            </h2>

            <p class="mt-3 max-w-xl text-sm leading-6 text-[#806d70]">
                @if($remainingProductSlots === 0)
                    Para seguir creciendo puedes activar productos ilimitados. El pago se confirma manualmente por Yape o BCP y luego el administrador activa tu tienda.
                @else
                    No necesitas esperar a llegar al límite. Puedes pagar ahora, enviar el comprobante y pedir la activación de productos ilimitados.
                @endif
            </p>

            <div class="mt-5 grid gap-3 sm:grid-cols-3">
                <div class="rounded-[20px] bg-[#fff8f1] px-4 py-4">
                    <p class="text-xs font-black uppercase tracking-[.08em] text-[#a08a89]">Usados</p>
                    <p class="mt-1 text-2xl font-black text-[#35272a]">{{ $productCount }}</p>
                </div>

                <div class="rounded-[20px] bg-[#fff8f1] px-4 py-4">
                    <p class="text-xs font-black uppercase tracking-[.08em] text-[#a08a89]">Gratis</p>
                    <p class="mt-1 text-2xl font-black text-[#35272a]">{{ $productLimit }}</p>
                </div>

                <div class="rounded-[20px] bg-[#e7f5ef] px-4 py-4">
                    <p class="text-xs font-black uppercase tracking-[.08em] text-[#427764]">Ilimitado</p>
                    <p class="mt-1 text-2xl font-black text-[#315c4d]">S/ {{ $planPrice }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-[26px] border border-[#eaded5] bg-[#fffdf9] p-5">
            <p class="text-sm font-black text-[#35272a]">
                Paga con Yape o BCP
            </p>

            <div class="mt-4 grid grid-cols-2 gap-3">
                <div class="rounded-[22px] border border-[#eaded5] bg-white p-4 text-center">
                    <img
                        src="{{ asset('images/payments/yape.png') }}"
                        alt="Yape"
                        class="mx-auto h-16 w-16 rounded-[18px] object-contain"
                    >
                    <p class="mt-3 text-xs font-black uppercase tracking-[.08em] text-[#8d797a]">Yape</p>
                </div>

                <div class="rounded-[22px] border border-[#eaded5] bg-white p-4 text-center">
                    <img
                        src="{{ asset('images/payments/bcp.png') }}"
                        alt="BCP"
                        class="mx-auto h-16 w-16 rounded-[18px] object-contain"
                    >
                    <p class="mt-3 text-xs font-black uppercase tracking-[.08em] text-[#8d797a]">BCP</p>
                </div>
            </div>

            <div class="mt-4 rounded-[20px] bg-[#fff3da] px-4 py-4">
                <p class="text-xs font-black uppercase tracking-[.08em] text-[#806226]">Número de pago</p>
                <p class="mt-1 text-3xl font-black tracking-[-.035em] text-[#35272a]">{{ $paymentNumber }}</p>
                <p class="mt-1 text-xs leading-5 text-[#806d70]">
                    Envía el comprobante para activar productos ilimitados.
                </p>
            </div>

            <div class="mt-4 grid gap-3">
                <a
                    href="{{ route('seller.payments.index') }}"
                    class="inline-flex w-full items-center justify-center rounded-[16px] bg-[#442d35] px-5 py-3.5 text-sm font-black text-white transition hover:bg-[#ff6b57]"
                >
                    Subir comprobante en el panel
                </a>

                <a
                    href="{{ $paymentWhatsappUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex w-full items-center justify-center rounded-[16px] bg-[#25D366] px-5 py-3.5 text-sm font-black text-white transition hover:bg-[#20bd5a]"
                >
                    Consultar por WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>
