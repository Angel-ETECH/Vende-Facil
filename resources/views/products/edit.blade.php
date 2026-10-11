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

                        Editando producto
                    </div>


                    <h1
                        class="mt-4 text-3xl font-black tracking-[-.04em] text-[#35272a] sm:text-4xl"
                    >
                        Editar producto
                    </h1>


                    <p class="mt-2 text-base text-[#806d70]">
                        Actualiza la información de
                        <span class="font-black text-[#ff604c]">
                            {{ $product->name }}
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
                action="{{ route('products.update', $product) }}"
                method="POST"
                enctype="multipart/form-data"
                class="mt-8"
                x-data="{
                    imagePreview: null,
                    currentImage: @js($product->image ? asset('storage/' . $product->image) : null),
                    productName: @js(old('name', $product->name)),
                    price: @js(old('price', $product->price)),
                    stock: @js(old('stock', $product->stock)),
                    categoryId: @js((string) old('category_id', $product->category_id)),
                    categoryName: @js($categories->firstWhere('id', old('category_id', $product->category_id))?->name ?? ''),

                    previewImage(event) {

                        const file = event.target.files[0];

                        if (!file) {
                            this.imagePreview = null;
                            return;
                        }

                        const reader = new FileReader();

                        reader.onload = (e) => {
                            this.imagePreview = e.target.result;
                        };

                        reader.readAsDataURL(file);
                    },

                    removeImage() {

                        this.imagePreview = null;

                        if (this.$refs.imageInput) {
                            this.$refs.imageInput.value = '';
                        }

                    }
                }"
            >

                @csrf
                @method('PUT')


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
                                class="text-xl font-black text-[#35272a]"
                            >
                                Información del producto
                            </h2>


                            <div class="mt-7 space-y-6">

                                {{-- NOMBRE --}}
                                <div>

                                    <label
                                        for="name"
                                        class="mb-2 block text-sm font-black text-[#514043]"
                                    >
                                        Nombre
                                    </label>

                                    <input
                                        type="text"
                                        name="name"
                                        id="name"
                                        value="{{ old('name', $product->name) }}"
                                        x-model="productName"
                                        required
                                        class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3.5 text-[#3d3032] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
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
                                        class="w-full resize-none rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3.5 text-[#3d3032] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                                    >{{ old('description', $product->description) }}</textarea>

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
                                                value="{{ old('price', $product->price) }}"
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
                                            value="{{ old('stock', $product->stock) }}"
                                            x-model="stock"
                                            required
                                            class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3.5 text-[#3d3032] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                                        >

                                    </div>

                                </div>


                                {{-- ACTIVO --}}
                                <div
                                    class="rounded-[20px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-4"
                                >

                                    <label
                                        for="active"
                                        class="flex cursor-pointer items-start gap-4"
                                    >

                                        <input
                                            id="active"
                                            type="checkbox"
                                            name="active"
                                            value="1"
                                            class="mt-1 rounded border-[#d6c5bc] text-[#ff6b57] focus:ring-[#ff8b78]"
                                            @checked(old('active', $product->active))
                                        >

                                        <div>

                                            <p class="font-black text-[#514043]">
                                                Producto activo
                                            </p>

                                            <p
                                                class="mt-1 text-sm leading-6 text-[#806d70]"
                                            >
                                                El producto será visible en la tienda pública.
                                            </p>

                                        </div>

                                    </label>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- IMAGEN --}}
                    <div
                        class="h-fit overflow-hidden rounded-[28px] border border-[#eaded5] bg-white shadow-[0_16px_40px_rgba(91,53,43,.07)]"
                    >

                        <div class="p-6">

                            <h2 class="text-xl font-black text-[#35272a]">
                                Imagen
                            </h2>


                            <div class="mt-6">

                                <template x-if="imagePreview">

                                    <div
                                        class="relative overflow-hidden rounded-[22px]"
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


                                <template x-if="!imagePreview">

                                    <div>

                                        @if ($product->image)

                                            <img
                                                src="{{ asset('storage/' . $product->image) }}"
                                                alt="{{ $product->name }}"
                                                class="h-[260px] w-full rounded-[22px] object-cover"
                                            >

                                        @else

                                            <label
                                                for="image"
                                                class="flex min-h-[260px] cursor-pointer flex-col items-center justify-center rounded-[22px] border-2 border-dashed border-[#decabf] bg-[#fffaf5] px-6 text-center"
                                            >
                                                <p class="font-black text-[#514043]">
                                                    Agregar imagen
                                                </p>
                                            </label>

                                        @endif

                                    </div>

                                </template>

                            </div>


                            <label
                                for="image"
                                class="mt-4 flex cursor-pointer items-center justify-center rounded-[15px] border border-[#e4d6cd] px-4 py-3 text-sm font-black text-[#6f5b5e]"
                            >
                                {{ $product->image ? 'Cambiar imagen' : 'Seleccionar imagen' }}
                            </label>


                            <input
                                x-ref="imageInput"
                                type="file"
                                name="image"
                                id="image"
                                accept="image/jpeg,image/png,image/webp"
                                @change="previewImage($event)"
                                class="hidden"
                            >

                            <div class="mt-5">
                                <label
                                    for="gallery_images"
                                    class="mb-2 block text-sm font-black text-[#514043]"
                                >
                                    Fotos extra
                                </label>

                                @if(! empty($product->gallery_images))
                                    <div class="mb-4 grid grid-cols-2 gap-3">
                                        @foreach($product->gallery_images as $galleryImage)
                                            <label class="overflow-hidden rounded-[18px] border border-[#eaded5] bg-[#fffdf9]">
                                                <img
                                                    src="{{ asset('storage/' . $galleryImage) }}"
                                                    alt="Foto extra de {{ $product->name }}"
                                                    class="h-24 w-full object-cover"
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
                                        for="product_gallery_images"
                                        class="flex cursor-pointer items-center justify-between gap-3 rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3 text-sm font-bold text-[#514043] shadow-sm transition hover:border-[#ff6a5c] hover:bg-white focus-within:border-[#ff6a5c] focus-within:ring-4 focus-within:ring-[#ff6a5c]/15"
                                    >
                                        <span class="min-w-0 truncate" x-text="galleryCount ? `${galleryCount} foto(s) seleccionada(s)` : 'Agregar fotos extra'"></span>
                                        <span class="shrink-0 rounded-full bg-[#442d35] px-4 py-2 text-xs font-black text-white">
                                            Elegir
                                        </span>
                                    </label>

                                    <input
                                        id="product_gallery_images"
                                        type="file"
                                        name="gallery_images[]"
                                        accept="image/jpeg,image/png,image/webp"
                                        multiple
                                        class="sr-only"
                                        @change="galleryCount = $event.target.files.length"
                                    >
                                </div>

                                <p class="mt-2 text-xs leading-5 text-[#9b8788]">
                                    Conserva, quita o agrega fotos extra para este producto.
                                </p>
                            </div>

                        </div>


                        <div class="border-t border-[#f0e3da] bg-[#fffdf9] p-6">

                            <p class="text-xs font-black uppercase tracking-[.1em] text-[#a08a89]">
                                Vista previa
                            </p>

                            <div class="mt-4 overflow-hidden rounded-[20px] border border-[#eaded5] bg-white">

                                <template x-if="imagePreview || currentImage">
                                    <img
                                        :src="imagePreview || currentImage"
                                        alt="Vista previa"
                                        class="h-36 w-full object-cover"
                                    >
                                </template>

                                <template x-if="!imagePreview && !currentImage">
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
                        Guardar cambios
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>
