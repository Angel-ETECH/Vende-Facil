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
                            stroke-width="2"
                            stroke="currentColor"
                            class="h-4 w-4"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 4.5v15M4.5 12h15"
                            />
                        </svg>

                        Nuevo producto
                    </div>


                    <h1
                        class="mt-4 text-3xl font-black tracking-[-.04em] text-[#35272a] sm:text-4xl"
                    >
                        Agregar producto
                    </h1>


                    <p class="mt-2 text-base text-[#806d70]">
                        Añade un nuevo producto al catálogo de
                        <span class="font-black text-[#ff604c]">
                            {{ $store->name }}
                        </span>.
                    </p>

                </div>


                <a
                    href="{{ route('products.index') }}"
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

                    Volver
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
                action="{{ route('products.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="mt-8"
                x-data="{
                    imagePreview: null,
                    fileName: '',
                    productName: @js(old('name', '')),
                    price: @js(old('price', '')),
                    stock: @js(old('stock', 0)),
                    categoryId: @js((string) old('category_id', '')),
                    categoryName: @js($categories->firstWhere('id', old('category_id'))?->name ?? ''),

                    previewImage(event) {

                        const file = event.target.files[0];

                        if (!file) {
                            this.imagePreview = null;
                            this.fileName = '';
                            return;
                        }

                        this.fileName = file.name;

                        const reader = new FileReader();

                        reader.onload = (e) => {
                            this.imagePreview = e.target.result;
                        };

                        reader.readAsDataURL(file);
                    },

                    removeImage() {

                        this.imagePreview = null;
                        this.fileName = '';

                        if (this.$refs.imageInput) {
                            this.$refs.imageInput.value = '';
                        }
                    }
                }"
            >

                @csrf


                <div class="grid gap-6 lg:grid-cols-[1.4fr_.8fr]">

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
                                Información del producto
                            </h2>

                            <p class="mt-1 text-sm leading-6 text-[#806d70]">
                                Completa los datos que verán tus clientes.
                            </p>


                            <div class="mt-7 space-y-6">

                                {{-- NOMBRE --}}
                                <div>

                                    <label
                                        for="name"
                                        class="mb-2 block text-sm font-black text-[#514043]"
                                    >
                                        Nombre del producto
                                    </label>

                                    <input
                                        type="text"
                                        name="name"
                                        id="name"
                                        value="{{ old('name') }}"
                                        x-model="productName"
                                        required
                                        placeholder="Ej. Hamburguesa clásica"
                                        class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3.5 text-[#3d3032] placeholder:text-[#b7a3a0] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                                    >

                                </div>


                                {{-- CATEGORÍA --}}
                                <div>

                                    <label
                                        for="category_id"
                                        class="mb-2 block text-sm font-black text-[#514043]"
                                    >
                                        Categoría
                                    </label>

                                    <input
                                        type="hidden"
                                        name="category_id"
                                        id="category_id"
                                        required
                                        :value="categoryId"
                                    >

                                    <div
                                        class="relative"
                                        x-data="{ open: false }"
                                        @click.outside="open = false"
                                    >
                                        <button
                                            type="button"
                                            @click="open = !open"
                                            class="flex w-full items-center justify-between gap-3 rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3.5 text-left text-[#3d3032] shadow-sm transition hover:border-[#ffd2c8] hover:bg-white focus:border-[#ff8b78] focus:outline-none focus:ring-2 focus:ring-[#ff8b78]/25"
                                        >
                                            <span
                                                class="truncate"
                                                :class="categoryName ? 'font-black text-[#3d3032]' : 'font-semibold text-[#b7a3a0]'"
                                                x-text="categoryName || 'Selecciona una categoría'"
                                            ></span>

                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="2"
                                                stroke="currentColor"
                                                class="h-4 w-4 flex-shrink-0 text-[#7e6a6d] transition"
                                                :class="{ 'rotate-180': open }"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="m6 9 6 6 6-6"
                                                />
                                            </svg>
                                        </button>

                                        <div
                                            x-show="open"
                                            x-cloak
                                            x-transition.origin.top
                                            class="absolute left-0 right-0 z-40 mt-2 max-h-72 overflow-y-auto rounded-[18px] border border-[#eaded5] bg-white p-2 shadow-[0_18px_45px_rgba(91,53,43,.16)]"
                                        >
                                            @foreach ($categories as $category)
                                                <button
                                                    type="button"
                                                    @click="categoryId = @js((string) $category->id); categoryName = @js($category->name); open = false"
                                                    class="flex w-full items-center justify-between rounded-[14px] px-4 py-3 text-left text-sm font-black transition hover:bg-[#fff3e8] {{ ! $loop->first ? 'mt-1' : '' }}"
                                                    :class="categoryId === @js((string) $category->id) ? 'bg-[#ff6b57] text-white' : 'text-[#4b3a3d]'"
                                                >
                                                    {{ $category->name }}
                                                    <span x-show="categoryId === @js((string) $category->id)">✓</span>
                                                </button>
                                            @endforeach
                                        </div>
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
                                        name="description"
                                        id="description"
                                        rows="5"
                                        placeholder="Describe brevemente tu producto..."
                                        class="w-full resize-none rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3.5 text-[#3d3032] placeholder:text-[#b7a3a0] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                                    >{{ old('description') }}</textarea>

                                </div>


                                {{-- PRECIO + STOCK --}}
                                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                                    <div>

                                        <label
                                            for="price"
                                            class="mb-2 block text-sm font-black text-[#514043]"
                                        >
                                            Precio
                                        </label>

                                        <div class="relative">

                                            <span
                                                class="absolute inset-y-0 left-0 flex items-center pl-4 text-sm font-black text-[#ff604c]"
                                            >
                                                S/
                                            </span>

                                            <input
                                                type="number"
                                                name="price"
                                                id="price"
                                                step="0.01"
                                                min="0"
                                                value="{{ old('price') }}"
                                                x-model="price"
                                                required
                                                class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] py-3.5 pl-12 pr-4 text-[#3d3032] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                                            >

                                        </div>

                                    </div>


                                    <div>

                                        <label
                                            for="stock"
                                            class="mb-2 block text-sm font-black text-[#514043]"
                                        >
                                            Stock
                                        </label>

                                        <input
                                            type="number"
                                            name="stock"
                                            id="stock"
                                            min="0"
                                            value="{{ old('stock', 0) }}"
                                            x-model="stock"
                                            required
                                            class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3.5 text-[#3d3032] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                                        >

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- IMAGEN --}}
                    <div
                        class="h-fit overflow-hidden rounded-[28px] border border-[#eaded5] bg-white shadow-[0_16px_40px_rgba(91,53,43,.07)]"
                    >

                        <div class="p-6">

                            <h2
                                class="text-xl font-black text-[#35272a]"
                            >
                                Imagen
                            </h2>

                            <p class="mt-1 text-sm text-[#806d70]">
                                Añade una foto clara de tu producto.
                            </p>


                            <div class="mt-6">

                                <template x-if="!imagePreview">

                                    <label
                                        for="image"
                                        class="flex min-h-[260px] cursor-pointer flex-col items-center justify-center rounded-[22px] border-2 border-dashed border-[#decabf] bg-[#fffaf5] px-6 text-center transition hover:border-[#ff9b88] hover:bg-[#fff5ed]"
                                    >

                                        <div
                                            class="flex h-16 w-16 items-center justify-center rounded-[20px] bg-[#fff0e4] text-[#ff6b57]"
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
                                            Seleccionar imagen
                                        </p>

                                        <p
                                            class="mt-1 text-xs text-[#9b8788]"
                                        >
                                            JPG, PNG o WEBP
                                        </p>

                                    </label>

                                </template>


                                <template x-if="imagePreview">

                                    <div
                                        class="relative overflow-hidden rounded-[22px] border border-[#eaded5]"
                                    >

                                        <img
                                            :src="imagePreview"
                                            class="h-[260px] w-full object-cover"
                                        >

                                        <button
                                            type="button"
                                            @click="removeImage()"
                                            class="absolute right-4 top-4 flex h-10 w-10 items-center justify-center rounded-full bg-white font-black text-[#d85845] shadow"
                                        >
                                            ×
                                        </button>

                                    </div>

                                </template>

                            </div>


                            <input
                                x-ref="imageInput"
                                type="file"
                                name="image"
                                id="image"
                                accept="image/jpeg,image/png,image/webp"
                                @change="previewImage($event)"
                                class="hidden"
                            >

                        </div>


                        <div class="border-t border-[#f0e3da] bg-[#fffdf9] p-6">

                            <p class="text-xs font-black uppercase tracking-[.1em] text-[#a08a89]">
                                Vista previa
                            </p>

                            <div class="mt-4 overflow-hidden rounded-[20px] border border-[#eaded5] bg-white">

                                <template x-if="imagePreview">
                                    <img
                                        :src="imagePreview"
                                        alt="Vista previa"
                                        class="h-36 w-full object-cover"
                                    >
                                </template>

                                <template x-if="!imagePreview">
                                    <div class="flex h-36 items-center justify-center bg-[#f6e8de] text-[#bf8d77]">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="h-10 w-10"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M3.75 6.75h16.5v12H3.75v-12Zm3-3h10.5l3 3H3.75l3-3Z"
                                            />
                                        </svg>
                                    </div>
                                </template>

                                <div class="p-4">
                                    <p
                                        class="truncate text-base font-black text-[#35272a]"
                                        x-text="productName || 'Nombre del producto'"
                                    ></p>

                                    <p
                                        class="mt-1 text-xs font-bold text-[#927e80]"
                                        x-text="categoryName || 'Categoría pendiente'"
                                    ></p>

                                    <div class="mt-4 flex items-end justify-between gap-3">
                                        <p class="text-xl font-black text-[#ff604c]">
                                            S/ <span x-text="price || '0.00'"></span>
                                        </p>

                                        <span class="rounded-full bg-[#fff3da] px-3 py-1 text-xs font-black text-[#806226]">
                                            Stock <span x-text="stock || 0"></span>
                                        </span>
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
                        href="{{ route('products.index') }}"
                        class="rounded-[15px] border border-[#e4d6cd] px-6 py-3.5 text-center text-sm font-black text-[#6f5b5e]"
                    >
                        Cancelar
                    </a>


                    <button
                        type="submit"
                        class="rounded-[15px] bg-[#ff6b57] px-7 py-3.5 text-sm font-black text-white transition hover:bg-[#ed5542]"
                    >
                        Guardar producto
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>
