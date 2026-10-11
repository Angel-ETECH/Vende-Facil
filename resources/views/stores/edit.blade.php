<x-app-layout>

    <div class="min-h-screen bg-[#fffaf5]">

        <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">

            {{-- ENCABEZADO --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <div
                        class="inline-flex items-center gap-2 rounded-full bg-[#ffe3a3] px-4 py-2 text-xs font-black uppercase tracking-[.12em] text-[#72581f]"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.8"
                            stroke="currentColor"
                            class="h-4 w-4"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m16.862 4.487 2.651 2.651M18.75 3.75a1.875 1.875 0 0 1 2.651 2.651L8.25 19.552 3.75 20.25l.698-4.5L17.599 2.599"
                            />
                        </svg>

                        Configuración
                    </div>

                    <h1
                        class="mt-4 text-3xl font-black tracking-[-.04em] text-[#35272a] sm:text-4xl"
                    >
                        Editar tienda
                    </h1>

                    <p class="mt-2 text-base text-[#806d70]">
                        Actualiza la información de
                        <span class="font-black text-[#ff604c]">
                            {{ $store->name }}
                        </span>.
                    </p>

                </div>

                <a
                    href="{{ route('dashboard') }}"
                    class="inline-flex w-fit items-center gap-2 rounded-full border border-[#e4d6cd] bg-white px-5 py-3 text-sm font-black text-[#6d595c] transition hover:bg-[#f8eee7]"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        stroke="currentColor"
                        class="h-4 w-4"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"
                        />
                    </svg>

                    Volver al panel
                </a>

            </div>


            {{-- ERRORES --}}
            @if ($errors->any())

                <div
                    class="mt-6 rounded-[18px] border border-[#f3c5bc] bg-[#fff0ec] px-5 py-4 text-sm text-[#a24536]"
                >

                    <p class="font-black">
                        Revisa la información ingresada.
                    </p>

                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>

            @endif


            <form
                action="{{ route('stores.update') }}"
                method="POST"
                enctype="multipart/form-data"
                class="mt-8"
                x-data="{
                    logoPreview: null,
                    currentLogo: @js($store->logo ? asset('storage/' . $store->logo) : null),
                    fileName: '',
                    storeName: @js(old('name', $store->name)),
                    description: @js(old('description', $store->description)),
                    city: @js(old('city', $store->city)),
                    address: @js(old('address', $store->address)),
                    deliveryAvailable: @js((bool) old('delivery_available', $store->delivery_available)),

                    previewLogo(event) {
                        const file = event.target.files[0];

                        if (!file) {
                            this.logoPreview = null;
                            this.fileName = '';
                            return;
                        }

                        this.fileName = file.name;

                        const reader = new FileReader();

                        reader.onload = (e) => {
                            this.logoPreview = e.target.result;
                        };

                        reader.readAsDataURL(file);
                    },

                    removeLogo() {
                        this.logoPreview = null;
                        this.fileName = '';

                        if (this.$refs.logoInput) {
                            this.$refs.logoInput.value = '';
                        }
                    }
                }"
            >

                @csrf
                @method('PUT')


                <div class="grid gap-6 lg:grid-cols-[1.35fr_.75fr]">

                    {{-- INFORMACIÓN --}}
                    <div
                        class="overflow-hidden rounded-[28px] border border-[#eaded5] bg-white shadow-[0_16px_40px_rgba(91,53,43,.07)]"
                    >

                        <div
                            class="h-[6px] bg-gradient-to-r from-[#ff7f6b] via-[#ffae78] to-[#ffd66b]"
                        ></div>

                        <div class="p-6 sm:p-7">

                            <h2
                                class="text-xl font-black tracking-[-.025em] text-[#35272a]"
                            >
                                Información del negocio
                            </h2>

                            <p class="mt-1 text-sm leading-6 text-[#806d70]">
                                Estos datos se muestran públicamente en tu tienda.
                            </p>


                            <div class="mt-7 space-y-6">

                                {{-- NOMBRE --}}
                                <div>

                                    <label
                                        for="name"
                                        class="mb-2 block text-sm font-black text-[#514043]"
                                    >
                                        Nombre de la tienda
                                    </label>

                                    <input
                                        id="name"
                                        type="text"
                                        name="name"
                                        value="{{ old('name', $store->name) }}"
                                        x-model="storeName"
                                        required
                                        class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3.5 text-[#3d3032] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                                    >

                                </div>


                                {{-- DESCRIPCIÓN --}}
                                <div>

                                    <label
                                        for="description"
                                        class="mb-2 block text-sm font-black text-[#514043]"
                                    >
                                        Descripción
                                    </label>

                                    <textarea
                                        id="description"
                                        name="description"
                                        rows="5"
                                        x-model="description"
                                        class="w-full resize-none rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3.5 text-[#3d3032] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                                    >{{ old('description', $store->description) }}</textarea>

                                </div>


                                {{-- WHATSAPP --}}
                                <div>

                                    <label
                                        for="whatsapp"
                                        class="mb-2 block text-sm font-black text-[#514043]"
                                    >
                                        Número de WhatsApp Perú
                                    </label>

                                    <input
                                        id="whatsapp"
                                        type="text"
                                        name="whatsapp"
                                        value="{{ old('whatsapp', $store->whatsapp) }}"
                                        required
                                        placeholder="Ej. 987654321 o 51987654321"
                                        class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3.5 text-[#3d3032] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                                    >

                                    <p class="mt-2 text-xs text-[#9b8788]">
                                        VendeFácil está enfocado en Perú. Puedes escribir tu número peruano con o sin 51.
                                    </p>

                                </div>


                                {{-- CIUDAD --}}
                                <div>

                                    <label
                                        for="city"
                                        class="mb-2 block text-sm font-black text-[#514043]"
                                    >
                                        Ciudad
                                    </label>

                                    <input
                                        id="city"
                                        type="text"
                                        name="city"
                                        value="{{ old('city', $store->city) }}"
                                        x-model="city"
                                        required
                                        placeholder="Ej. Lima, Arequipa, Pucallpa"
                                        class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3.5 text-[#3d3032] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                                    >

                                </div>


                                {{-- DIRECCIÓN --}}
                                <div>

                                    <label
                                        for="address"
                                        class="mb-2 block text-sm font-black text-[#514043]"
                                    >
                                        Dirección o referencia
                                    </label>

                                    <input
                                        id="address"
                                        type="text"
                                        name="address"
                                        value="{{ old('address', $store->address) }}"
                                        x-model="address"
                                        placeholder="Ej. Av. Principal 123, Urb. Centro"
                                        class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3.5 text-[#3d3032] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                                    >

                                    <p class="mt-2 text-xs leading-5 text-[#9b8788]">
                                        Si no haces delivery, este dato es obligatorio para que los clientes sepan dónde comprar.
                                    </p>

                                </div>


                                {{-- UBICACIÓN EXACTA --}}
                                <div>

                                    <label
                                        for="location_url"
                                        class="mb-2 block text-sm font-black text-[#514043]"
                                    >
                                        Enlace de Google Maps
                                    </label>

                                    <input
                                        id="location_url"
                                        type="url"
                                        name="location_url"
                                        value="{{ old('location_url', $store->location_url) }}"
                                        placeholder="Pega aquí el enlace de tu ubicación exacta"
                                        class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3.5 text-[#3d3032] placeholder:text-[#b7a3a0] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                                    >

                                    <p class="mt-2 text-xs leading-5 text-[#9b8788]">
                                        Este enlace se usa en el botón “Cómo llegar” de tu tienda pública.
                                    </p>

                                </div>


                                {{-- HORARIOS --}}
                                <div>

                                    <label
                                        for="opening_hours"
                                        class="mb-2 block text-sm font-black text-[#514043]"
                                    >
                                        Horario de atención
                                    </label>

                                    <input
                                        id="opening_hours"
                                        type="text"
                                        name="opening_hours"
                                        value="{{ old('opening_hours', $store->opening_hours) }}"
                                        placeholder="Ej. Lun a sáb, 9:00 a. m. - 8:00 p. m."
                                        class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3.5 text-[#3d3032] placeholder:text-[#b7a3a0] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                                    >

                                </div>


                                {{-- REDES --}}
                                <div class="grid gap-5 sm:grid-cols-2">

                                    <div>
                                        <label for="facebook_url" class="mb-2 block text-sm font-black text-[#514043]">Facebook</label>
                                        <input id="facebook_url" type="url" name="facebook_url" value="{{ old('facebook_url', $store->facebook_url) }}" placeholder="https://facebook.com/tu-tienda" class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3.5 text-[#3d3032] placeholder:text-[#b7a3a0] focus:border-[#ff8b78] focus:ring-[#ff8b78]">
                                    </div>

                                    <div>
                                        <label for="instagram_url" class="mb-2 block text-sm font-black text-[#514043]">Instagram</label>
                                        <input id="instagram_url" type="url" name="instagram_url" value="{{ old('instagram_url', $store->instagram_url) }}" placeholder="https://instagram.com/tu-tienda" class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3.5 text-[#3d3032] placeholder:text-[#b7a3a0] focus:border-[#ff8b78] focus:ring-[#ff8b78]">
                                    </div>

                                    <div>
                                        <label for="tiktok_url" class="mb-2 block text-sm font-black text-[#514043]">TikTok</label>
                                        <input id="tiktok_url" type="url" name="tiktok_url" value="{{ old('tiktok_url', $store->tiktok_url) }}" placeholder="https://www.tiktok.com/@tu-tienda" class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3.5 text-[#3d3032] placeholder:text-[#b7a3a0] focus:border-[#ff8b78] focus:ring-[#ff8b78]">
                                    </div>

                                    <div>
                                        <label for="website_url" class="mb-2 block text-sm font-black text-[#514043]">Página web</label>
                                        <input id="website_url" type="url" name="website_url" value="{{ old('website_url', $store->website_url) }}" placeholder="https://tu-dominio.com" class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3.5 text-[#3d3032] placeholder:text-[#b7a3a0] focus:border-[#ff8b78] focus:ring-[#ff8b78]">
                                    </div>

                                </div>


                                {{-- DELIVERY --}}
                                <div
                                    class="rounded-[18px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-4"
                                >

                                    <label class="flex cursor-pointer items-start gap-3">
                                        <input
                                            type="checkbox"
                                            name="delivery_available"
                                            value="1"
                                            x-model="deliveryAvailable"
                                            @checked(old('delivery_available', $store->delivery_available))
                                            class="mt-1 rounded border-[#d8c8bf] text-[#ff6b57] focus:ring-[#ff8b78]"
                                        >

                                        <span>
                                            <span class="block text-sm font-black text-[#514043]">
                                                ¿Haces delivery?
                                            </span>

                                            <span class="mt-1 block text-xs leading-5 text-[#8d797a]">
                                                Marca esta opción si puedes enviar pedidos dentro de tu ciudad o zona de atención.
                                            </span>
                                        </span>
                                    </label>

                                </div>


                                {{-- GALERÍA --}}
                                <div>
                                    <label
                                        for="gallery_images"
                                        class="mb-2 block text-sm font-black text-[#514043]"
                                    >
                                        Fotos extra de la tienda
                                    </label>

                                    @if(! empty($store->gallery_images))
                                        <div class="mb-4 grid grid-cols-2 gap-3 sm:grid-cols-3">
                                            @foreach($store->gallery_images as $galleryImage)
                                                <label class="group relative overflow-hidden rounded-[18px] border border-[#eaded5] bg-[#fffdf9]">
                                                    <img
                                                        src="{{ asset('storage/' . $galleryImage) }}"
                                                        alt="Foto extra de {{ $store->name }}"
                                                        class="h-28 w-full object-cover"
                                                    >
                                                    <span class="flex items-center gap-2 px-3 py-2 text-xs font-bold text-[#806d70]">
                                                        <input
                                                            type="checkbox"
                                                            name="remove_gallery_images[]"
                                                            value="{{ $galleryImage }}"
                                                            class="rounded border-[#d8c8bf] text-[#ff6b57] focus:ring-[#ff8b78]"
                                                        >
                                                        Quitar
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div x-data="{ galleryCount: 0 }">
                                        <label
                                            for="store_gallery_images"
                                            class="flex cursor-pointer items-center justify-between gap-3 rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3 text-sm font-bold text-[#514043] shadow-sm transition hover:border-[#ff6a5c] hover:bg-white focus-within:border-[#ff6a5c] focus-within:ring-4 focus-within:ring-[#ff6a5c]/15"
                                        >
                                            <span class="min-w-0 truncate" x-text="galleryCount ? `${galleryCount} foto(s) seleccionada(s)` : 'Agregar fotos extra'"></span>
                                            <span class="shrink-0 rounded-full bg-[#442d35] px-4 py-2 text-xs font-black text-white">
                                                Elegir
                                            </span>
                                        </label>

                                        <input
                                            id="store_gallery_images"
                                            type="file"
                                            name="gallery_images[]"
                                            accept="image/jpeg,image/png,image/webp"
                                            multiple
                                            class="sr-only"
                                            @change="galleryCount = $event.target.files.length"
                                        >
                                    </div>

                                    <p class="mt-2 text-xs leading-5 text-[#9b8788]">
                                        Puedes conservar, quitar o sumar fotos extra. Se mostrarán en tu tienda pública.
                                    </p>
                                </div>


                                {{-- ENLACE --}}
                                <div
                                    class="rounded-[18px] bg-[#fff5e8] px-4 py-3"
                                >

                                    <p
                                        class="text-xs font-bold uppercase tracking-[.1em] text-[#9a7a33]"
                                    >
                                        Enlace público
                                    </p>

                                    <p
                                        class="mt-1 break-all text-sm font-black text-[#5a474a]"
                                    >
                                        {{ route('stores.show', $store) }}
                                    </p>

                                    <p
                                        class="mt-2 text-xs leading-5 text-[#8f797b]"
                                    >
                                        Este enlace se mantiene aunque cambies el nombre de tu tienda.
                                    </p>

                                    <button
                                        type="button"
                                        x-data="{ copied: false, url: @js(route('stores.show', $store)) }"
                                        @click="navigator.clipboard.writeText(url); copied = true; setTimeout(() => copied = false, 1800)"
                                        class="mt-3 inline-flex rounded-full bg-[#442d35] px-4 py-2 text-xs font-black text-white transition hover:bg-[#ff6b57]"
                                        x-text="copied ? 'Copiado' : 'Copiar enlace'"
                                    ></button>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- LOGO --}}
                    <div
                        class="h-fit overflow-hidden rounded-[28px] border border-[#eaded5] bg-white shadow-[0_16px_40px_rgba(91,53,43,.07)]"
                    >

                        <div class="p-6">

                            <h2 class="text-xl font-black text-[#35272a]">
                                Logo
                            </h2>

                            <p class="mt-1 text-sm text-[#806d70]">
                                Conserva el actual o selecciona uno nuevo.
                            </p>


                            <div class="mt-6">

                                <template x-if="logoPreview">

                                    <div
                                        class="relative flex min-h-[260px] items-center justify-center rounded-[22px] bg-[#fffaf5] p-8"
                                    >

                                        <img
                                            :src="logoPreview"
                                            alt="Nuevo logo"
                                            class="h-44 w-44 rounded-[32px] object-cover shadow-lg"
                                        >

                                        <button
                                            type="button"
                                            @click="removeLogo()"
                                            class="absolute right-4 top-4 flex h-10 w-10 items-center justify-center rounded-full bg-white text-[#d85845] shadow"
                                        >
                                            ×
                                        </button>

                                    </div>

                                </template>


                                <template x-if="!logoPreview">

                                    <div>

                                        @if($store->logo)

                                            <div
                                                class="flex min-h-[260px] items-center justify-center rounded-[22px] bg-[#fffaf5] p-8"
                                            >

                                                <img
                                                    src="{{ asset('storage/' . $store->logo) }}"
                                                    alt="{{ $store->name }}"
                                                    class="h-44 w-44 rounded-[32px] object-cover shadow-lg"
                                                >

                                            </div>

                                        @else

                                            <label
                                                for="logo"
                                                class="flex min-h-[260px] cursor-pointer flex-col items-center justify-center rounded-[22px] border-2 border-dashed border-[#decabf] bg-[#fffaf5] px-6 text-center transition hover:border-[#ff9b88]"
                                            >

                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.7"
                                                    stroke="currentColor"
                                                    class="h-10 w-10 text-[#ff6b57]"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="m3 16.5 5.25-5.25a2.25 2.25 0 0 1 3.182 0l1.318 1.318m0 0L15 10.318a2.25 2.25 0 0 1 3.182 0L21 13.136M3.75 4.5h16.5v15H3.75v-15Z"
                                                    />
                                                </svg>

                                                <p class="mt-4 font-black text-[#514043]">
                                                    Agregar logo
                                                </p>

                                            </label>

                                        @endif

                                    </div>

                                </template>

                            </div>


                            <label
                                for="logo"
                                class="mt-4 flex cursor-pointer items-center justify-center rounded-[15px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3 text-sm font-black text-[#6f5b5e] transition hover:bg-[#f8eee7]"
                            >
                                {{ $store->logo ? 'Cambiar logo' : 'Seleccionar logo' }}
                            </label>


                            <input
                                x-ref="logoInput"
                                id="logo"
                                type="file"
                                name="logo"
                                accept="image/jpeg,image/png,image/webp"
                                @change="previewLogo($event)"
                                class="hidden"
                            >

                        </div>


                        <div class="border-t border-[#f0e3da] bg-[#fffdf9] p-6">

                            <p class="text-xs font-black uppercase tracking-[.1em] text-[#a08a89]">
                                Vista previa
                            </p>

                            <div class="mt-4 overflow-hidden rounded-[22px] border border-[#eaded5] bg-white">

                                <div class="h-3 bg-gradient-to-r from-[#ff7f6b] via-[#ffae78] to-[#ffd66b]"></div>

                                <div class="p-5">

                                    <div class="flex items-start gap-4">

                                        <template x-if="logoPreview || currentLogo">
                                            <img
                                                :src="logoPreview || currentLogo"
                                                alt="Vista previa del logo"
                                                class="h-16 w-16 flex-shrink-0 rounded-[18px] object-cover"
                                            >
                                        </template>

                                        <template x-if="!logoPreview && !currentLogo">
                                            <div class="flex h-16 w-16 flex-shrink-0 items-center justify-center rounded-[18px] bg-[#ffd66b] text-xl font-black text-[#5e4723]">
                                                <span x-text="(storeName || 'T').charAt(0).toUpperCase()"></span>
                                            </div>
                                        </template>


                                        <div class="min-w-0 flex-1">

                                            <p
                                                class="truncate text-lg font-black tracking-[-.03em] text-[#35272a]"
                                                x-text="storeName || 'Nombre de la tienda'"
                                            ></p>

                                            <p class="mt-1 text-xs font-bold text-[#927e80]">
                                                <span x-text="city || 'Ciudad'"></span>, Perú
                                            </p>

                                        </div>

                                    </div>


                                    <p
                                        class="mt-4 line-clamp-3 text-sm leading-6 text-[#806d70]"
                                        x-text="description || 'Describe aquí qué vendes, qué te hace diferente y cómo pueden comprarte.'"
                                    ></p>


                                    <div class="mt-4 flex flex-wrap gap-2">

                                        <span
                                            class="rounded-full bg-[#fff3da] px-3 py-1.5 text-xs font-black text-[#806226]"
                                            x-text="address || 'Dirección o referencia'"
                                        ></span>

                                        <span
                                            class="rounded-full px-3 py-1.5 text-xs font-black"
                                            :class="deliveryAvailable ? 'bg-[#e7f5ef] text-[#427764]' : 'bg-[#eee5df] text-[#8b7678]'"
                                            x-text="deliveryAvailable ? 'Hace delivery' : 'Sin delivery'"
                                        ></span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- BOTONES --}}
                <div
                    class="mt-6 flex flex-col-reverse gap-3 rounded-[24px] border border-[#eaded5] bg-white p-4 sm:flex-row sm:justify-end"
                >

                    <a
                        href="{{ route('dashboard') }}"
                        class="rounded-[15px] border border-[#e4d6cd] px-6 py-3.5 text-center text-sm font-black text-[#6f5b5e]"
                    >
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="rounded-[15px] bg-[#ff6b57] px-7 py-3.5 text-sm font-black text-white transition hover:bg-[#ed5542]"
                    >
                        Guardar cambios
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>
