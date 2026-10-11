<x-app-layout>

    <div class="min-h-screen bg-[#fffaf5]">

        <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">

            {{-- ================================================= --}}
            {{-- ENCABEZADO --}}
            {{-- ================================================= --}}

            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
            >

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
                                d="M3.75 9.75 12 4.5l8.25 5.25M5.25 8.8V19.5h13.5V8.8M9 19.5v-5.25h6v5.25"
                            />
                        </svg>

                        Tu espacio
                    </div>


                    <h1
                        class="mt-4 text-3xl font-black tracking-[-.04em] text-[#35272a] sm:text-4xl"
                    >
                        Crea tu tienda
                    </h1>


                    <p
                        class="mt-2 max-w-2xl text-base leading-7 text-[#806d70]"
                    >
                        Completa la información principal de tu negocio.
                        Después podrás comenzar a publicar tus productos.
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


            {{-- ================================================= --}}
            {{-- ERRORES --}}
            {{-- ================================================= --}}

            @if ($errors->any())

                <div
                    class="mt-6 rounded-[18px] border border-[#f3c5bc] bg-[#fff0ec] px-5 py-4 text-sm text-[#a24536]"
                >

                    <div class="flex items-start gap-3">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                            class="mt-0.5 h-5 w-5 flex-shrink-0"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 9v3.75m9-1.5a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM12 15.75h.008v.008H12v-.008Z"
                            />
                        </svg>


                        <div>

                            <p class="font-black">
                                Revisa la información ingresada.
                            </p>

                            <ul class="mt-2 list-disc space-y-1 pl-5">

                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach

                            </ul>

                        </div>

                    </div>

                </div>

            @endif


            {{-- ================================================= --}}
            {{-- FORMULARIO --}}
            {{-- ================================================= --}}

            <form
                action="{{ route('stores.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="mt-8"
                x-data="{
                    logoPreview: null,
                    logoName: '',

                    previewLogo(event) {
                        const file = event.target.files[0];

                        if (!file) {
                            this.logoPreview = null;
                            this.logoName = '';
                            return;
                        }

                        this.logoName = file.name;

                        const reader = new FileReader();

                        reader.onload = (e) => {
                            this.logoPreview = e.target.result;
                        };

                        reader.readAsDataURL(file);
                    },

                    removeLogo() {
                        this.logoPreview = null;
                        this.logoName = '';

                        if (this.$refs.logoInput) {
                            this.$refs.logoInput.value = '';
                        }
                    }
                }"
            >

                @csrf


                <div
                    class="grid gap-6 lg:grid-cols-[1.35fr_.75fr]"
                >

                    {{-- ================================================= --}}
                    {{-- INFORMACIÓN --}}
                    {{-- ================================================= --}}

                    <div
                        class="overflow-hidden rounded-[28px] border border-[#eaded5] bg-white shadow-[0_16px_40px_rgba(91,53,43,.07)]"
                    >

                        <div
                            class="h-[6px] bg-gradient-to-r from-[#ff7f6b] via-[#ffae78] to-[#ffd66b]"
                        ></div>


                        <div class="p-6 sm:p-7">

                            <div>

                                <h2
                                    class="text-xl font-black tracking-[-.025em] text-[#35272a]"
                                >
                                    Información de tu negocio
                                </h2>

                                <p
                                    class="mt-1 text-sm leading-6 text-[#806d70]"
                                >
                                    Estos datos serán visibles para las personas que visiten tu tienda.
                                </p>

                            </div>


                            <div class="mt-7 space-y-6">

                                {{-- NOMBRE --}}
                                <div>

                                    <label
                                        for="name"
                                        class="mb-2 block text-sm font-black text-[#514043]"
                                    >
                                        Nombre de la tienda
                                    </label>


                                    <div class="relative">

                                        <div
                                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-[#b19795]"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.8"
                                                stroke="currentColor"
                                                class="h-5 w-5"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M3.75 9.75 12 4.5l8.25 5.25M5.25 8.8V19.5h13.5V8.8"
                                                />
                                            </svg>
                                        </div>


                                        <input
                                            id="name"
                                            type="text"
                                            name="name"
                                            value="{{ old('name') }}"
                                            required
                                            placeholder="Ej. Restaurante Pollito"
                                            class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] py-3.5 pl-12 pr-4 text-[#3d3032] placeholder:text-[#b7a3a0] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                                        >

                                    </div>

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
                                        placeholder="Cuéntales a tus clientes qué vendes y qué hace especial a tu negocio."
                                        class="w-full resize-none rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3.5 text-[#3d3032] placeholder:text-[#b7a3a0] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                                    >{{ old('description') }}</textarea>

                                    <p
                                        class="mt-2 text-xs leading-5 text-[#9b8788]"
                                    >
                                        Una descripción breve y clara ayuda a que las personas conozcan mejor tu tienda.
                                    </p>

                                </div>


                                {{-- WHATSAPP --}}
                                <div>

                                    <label
                                        for="whatsapp"
                                        class="mb-2 block text-sm font-black text-[#514043]"
                                    >
                                        Número de WhatsApp Perú
                                    </label>


                                    <div class="relative">

                                        <div
                                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-[#4e8b79]"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.8"
                                                stroke="currentColor"
                                                class="h-5 w-5"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M7.5 8.25h9m-9 3h6m-8.25 8.25 1.5-4.5a8.25 8.25 0 1 1 3 3l-4.5 1.5Z"
                                                />
                                            </svg>
                                        </div>


                                        <input
                                            id="whatsapp"
                                            type="text"
                                            name="whatsapp"
                                            value="{{ old('whatsapp') }}"
                                            required
                                            placeholder="Ej. 987654321 o 51987654321"
                                            class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] py-3.5 pl-12 pr-4 text-[#3d3032] placeholder:text-[#b7a3a0] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                                        >

                                    </div>


                                    <div
                                        class="mt-2 flex items-start gap-2 text-xs leading-5 text-[#8d797a]"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.8"
                                            stroke="currentColor"
                                            class="mt-0.5 h-4 w-4 flex-shrink-0 text-[#b38f3b]"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M12 9v3.75m0 3h.008v.008H12v-.008ZM12 3.75a8.25 8.25 0 1 1 0 16.5 8.25 8.25 0 0 1 0-16.5Z"
                                            />
                                        </svg>

                                        VendeFácil está enfocado en Perú. Puedes escribir tu número peruano con o sin 51.
                                    </div>

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
                                        value="{{ old('city') }}"
                                        required
                                        placeholder="Ej. Lima, Arequipa, Pucallpa"
                                        class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3.5 text-[#3d3032] placeholder:text-[#b7a3a0] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                                    >

                                    <p
                                        class="mt-2 text-xs leading-5 text-[#9b8788]"
                                    >
                                        Indica la ciudad de Perú donde atiende tu negocio.
                                    </p>

                                </div>


                                {{-- DIRECCIÓN --}}
                                <div>

                                    <label
                                        for="address"
                                        class="mb-2 block text-sm font-black text-[#514043]"
                                    >
                                        Dirección o referencia
                                    </label>


                                    <div class="relative">

                                        <div
                                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-[#ff6b57]"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.8"
                                                stroke="currentColor"
                                                class="h-5 w-5"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M12 21s6.75-4.2 6.75-10.5a6.75 6.75 0 1 0-13.5 0C5.25 16.8 12 21 12 21Z"
                                                />
                                                <circle
                                                    cx="12"
                                                    cy="10.5"
                                                    r="2"
                                                />
                                            </svg>
                                        </div>


                                        <input
                                            id="address"
                                            type="text"
                                            name="address"
                                            value="{{ old('address') }}"
                                            placeholder="Ej. Av. Principal 123, Urb. Centro"
                                            class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] py-3.5 pl-12 pr-4 text-[#3d3032] placeholder:text-[#b7a3a0] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                                        >

                                    </div>

                                    <p
                                        class="mt-2 text-xs leading-5 text-[#9b8788]"
                                    >
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
                                        value="{{ old('location_url') }}"
                                        placeholder="Pega aquí el enlace de tu ubicación exacta"
                                        class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3.5 text-[#3d3032] placeholder:text-[#b7a3a0] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                                    >

                                    <p class="mt-2 text-xs leading-5 text-[#9b8788]">
                                        Copia el enlace desde Google Maps para que tus clientes puedan tocar “Cómo llegar”.
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
                                        value="{{ old('opening_hours') }}"
                                        placeholder="Ej. Lun a sáb, 9:00 a. m. - 8:00 p. m."
                                        class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3.5 text-[#3d3032] placeholder:text-[#b7a3a0] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                                    >

                                </div>


                                {{-- REDES --}}
                                <div class="grid gap-5 sm:grid-cols-2">

                                    <div>
                                        <label for="facebook_url" class="mb-2 block text-sm font-black text-[#514043]">Facebook</label>
                                        <input id="facebook_url" type="url" name="facebook_url" value="{{ old('facebook_url') }}" placeholder="https://facebook.com/tu-tienda" class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3.5 text-[#3d3032] placeholder:text-[#b7a3a0] focus:border-[#ff8b78] focus:ring-[#ff8b78]">
                                    </div>

                                    <div>
                                        <label for="instagram_url" class="mb-2 block text-sm font-black text-[#514043]">Instagram</label>
                                        <input id="instagram_url" type="url" name="instagram_url" value="{{ old('instagram_url') }}" placeholder="https://instagram.com/tu-tienda" class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3.5 text-[#3d3032] placeholder:text-[#b7a3a0] focus:border-[#ff8b78] focus:ring-[#ff8b78]">
                                    </div>

                                    <div>
                                        <label for="tiktok_url" class="mb-2 block text-sm font-black text-[#514043]">TikTok</label>
                                        <input id="tiktok_url" type="url" name="tiktok_url" value="{{ old('tiktok_url') }}" placeholder="https://www.tiktok.com/@tu-tienda" class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3.5 text-[#3d3032] placeholder:text-[#b7a3a0] focus:border-[#ff8b78] focus:ring-[#ff8b78]">
                                    </div>

                                    <div>
                                        <label for="website_url" class="mb-2 block text-sm font-black text-[#514043]">Página web</label>
                                        <input id="website_url" type="url" name="website_url" value="{{ old('website_url') }}" placeholder="https://tu-dominio.com" class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3.5 text-[#3d3032] placeholder:text-[#b7a3a0] focus:border-[#ff8b78] focus:ring-[#ff8b78]">
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
                                            @checked(old('delivery_available'))
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
                                <div x-data="{ galleryCount: 0 }">
                                    <label
                                        for="store_gallery_images"
                                        class="mb-2 block text-sm font-black text-[#514043]"
                                    >
                                        Fotos extra de la tienda
                                    </label>
                                    <label
                                        for="store_gallery_images"
                                        class="flex cursor-pointer items-center justify-between gap-3 rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3 text-sm font-bold text-[#514043] shadow-sm transition hover:border-[#ff6a5c] hover:bg-white focus-within:border-[#ff6a5c] focus-within:ring-4 focus-within:ring-[#ff6a5c]/15"
                                    >
                                        <span class="min-w-0 truncate" x-text="galleryCount ? `${galleryCount} foto(s) seleccionada(s)` : 'Seleccionar fotos extra'"></span>
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

                                    <p class="mt-2 text-xs leading-5 text-[#9b8788]">
                                        Puedes subir hasta 6 fotos del local, fachada o ambiente. JPG, PNG o WEBP.
                                    </p>
                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- LOGO --}}
                    {{-- ================================================= --}}

                    <div
                        class="h-fit overflow-hidden rounded-[28px] border border-[#eaded5] bg-white shadow-[0_16px_40px_rgba(91,53,43,.07)]"
                    >

                        <div class="p-6">

                            <h2
                                class="text-xl font-black tracking-[-.025em] text-[#35272a]"
                            >
                                Logo de tu tienda
                            </h2>

                            <p
                                class="mt-1 text-sm leading-6 text-[#806d70]"
                            >
                                Dale personalidad a tu negocio con una imagen.
                            </p>


                            <div class="mt-6">

                                {{-- SIN PREVIEW --}}
                                <template x-if="!logoPreview">

                                    <label
                                        for="logo"
                                        class="group flex min-h-[260px] cursor-pointer flex-col items-center justify-center rounded-[22px] border-2 border-dashed border-[#decabf] bg-[#fffaf5] px-6 text-center transition hover:border-[#ff9b88] hover:bg-[#fff5ed]"
                                    >

                                        <div
                                            class="flex h-16 w-16 items-center justify-center rounded-[20px] bg-[#fff0e4] text-[#ff6b57] transition group-hover:scale-105"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.7"
                                                stroke="currentColor"
                                                class="h-7 w-7"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="m3 16.5 5.25-5.25a2.25 2.25 0 0 1 3.182 0l1.318 1.318m0 0L15 10.318a2.25 2.25 0 0 1 3.182 0L21 13.136M3.75 4.5h16.5v15H3.75v-15Z"
                                                />
                                            </svg>
                                        </div>


                                        <p
                                            class="mt-4 font-black text-[#514043]"
                                        >
                                            Seleccionar logo
                                        </p>

                                        <p
                                            class="mt-1 text-xs leading-5 text-[#9b8788]"
                                        >
                                            JPG, PNG o WEBP
                                            <br>
                                            máximo 2 MB
                                        </p>

                                    </label>

                                </template>


                                {{-- CON PREVIEW --}}
                                <template x-if="logoPreview">

                                    <div
                                        class="relative flex min-h-[260px] items-center justify-center overflow-hidden rounded-[22px] border border-[#eaded5] bg-[#fffaf5] p-8"
                                    >

                                        <img
                                            :src="logoPreview"
                                            alt="Vista previa del logo"
                                            class="h-44 w-44 rounded-[32px] object-cover shadow-lg"
                                        >


                                        <button
                                            type="button"
                                            @click="removeLogo()"
                                            class="absolute right-4 top-4 flex h-10 w-10 items-center justify-center rounded-full bg-white text-[#d85845] shadow transition hover:scale-105"
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
                                                    d="m6 6 12 12M18 6 6 18"
                                                />
                                            </svg>
                                        </button>


                                        <div
                                            class="absolute inset-x-4 bottom-4 truncate rounded-full bg-white/90 px-4 py-2 text-center text-xs font-bold text-[#655154] backdrop-blur"
                                            x-text="logoName"
                                        ></div>

                                    </div>

                                </template>


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


                            {{-- TIP --}}
                            <div
                                class="mt-5 rounded-[18px] bg-[#eaf6f1] px-4 py-3"
                            >

                                <div class="flex items-start gap-3">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                        class="mt-0.5 h-5 w-5 flex-shrink-0 text-[#4e8b79]"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 9v3.75m0 3h.008v.008H12v-.008ZM12 3.75a8.25 8.25 0 1 1 0 16.5 8.25 8.25 0 0 1 0-16.5Z"
                                        />
                                    </svg>

                                    <p
                                        class="text-xs leading-5 text-[#4e776b]"
                                    >
                                        Para que tu tienda se vea mejor, utiliza un logo cuadrado y con buena calidad.
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- BOTONES --}}
                {{-- ================================================= --}}

                <div
                    class="mt-6 flex flex-col-reverse gap-3 rounded-[24px] border border-[#eaded5] bg-white p-4 sm:flex-row sm:items-center sm:justify-end"
                >

                    <a
                        href="{{ route('dashboard') }}"
                        class="inline-flex items-center justify-center rounded-[15px] border border-[#e4d6cd] bg-[#fffdf9] px-6 py-3.5 text-sm font-black text-[#6f5b5e] transition hover:bg-[#f8eee7]"
                    >
                        Cancelar
                    </a>


                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-[15px] bg-[#ff6b57] px-7 py-3.5 text-sm font-black text-white shadow-lg shadow-orange-100 transition hover:bg-[#ed5542]"
                    >
                        Crear mi tienda

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
                                d="M5 12h14m-5-5 5 5-5 5"
                            />
                        </svg>
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>
