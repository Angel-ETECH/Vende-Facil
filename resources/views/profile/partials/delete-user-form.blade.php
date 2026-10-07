<section class="space-y-6">

    <header>

        <div class="flex items-start gap-4">

            <div
                class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-2xl bg-[#fff0ec] text-[#c45847]"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.8"
                    stroke="currentColor"
                    class="h-6 w-6"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6.75 7.5h10.5M9.75 7.5V5.25h4.5V7.5m-6 0 .75 11.25h6L15.75 7.5"
                    />
                </svg>
            </div>


            <div>

                <h2
                    class="text-xl font-black tracking-[-.025em] text-[#35272a]"
                >
                    Eliminar cuenta
                </h2>

                <p
                    class="mt-1 max-w-2xl text-sm leading-6 text-[#806d70]"
                >
                    Al eliminar tu cuenta, todos los datos asociados serán eliminados permanentemente.
                </p>

            </div>

        </div>

    </header>


    <div
        class="rounded-[18px] border border-[#f2d1ca] bg-[#fff7f5] px-4 py-3"
    >
        <p class="text-sm font-semibold leading-6 text-[#9d4f42]">
            Antes de eliminar tu cuenta, asegúrate de que ya no necesitas acceder a tu tienda, productos o métricas.
        </p>
    </div>


    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="rounded-[15px] bg-[#fff0ec] px-5 py-3 text-sm font-black text-[#b65040] transition hover:bg-[#ffe1da]"
    >
        Eliminar mi cuenta
    </button>


    <x-modal
        name="confirm-user-deletion"
        :show="$errors->userDeletion->isNotEmpty()"
        focusable
    >

        <form
            method="post"
            action="{{ route('profile.destroy') }}"
            class="p-6"
        >

            @csrf
            @method('delete')


            <h2
                class="text-xl font-black text-[#35272a]"
            >
                ¿Seguro que deseas eliminar tu cuenta?
            </h2>


            <p
                class="mt-3 text-sm leading-6 text-[#806d70]"
            >
                Esta acción no se puede deshacer. Ingresa tu contraseña
                para confirmar la eliminación.
            </p>


            <div class="mt-6">

                <label
                    for="password"
                    class="mb-2 block text-sm font-black text-[#514043]"
                >
                    Contraseña
                </label>

                <input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="Tu contraseña"
                    class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3.5 text-[#3d3032] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                >

                @if($errors->userDeletion->has('password'))
                    <p class="mt-2 text-sm font-semibold text-[#c34d3c]">
                        {{ $errors->userDeletion->first('password') }}
                    </p>
                @endif

            </div>


            <div class="mt-6 flex justify-end gap-3">

                <button
                    type="button"
                    x-on:click="$dispatch('close')"
                    class="rounded-[14px] border border-[#e4d6cd] bg-white px-5 py-3 text-sm font-black text-[#6d595c]"
                >
                    Cancelar
                </button>


                <button
                    type="submit"
                    class="rounded-[14px] bg-[#c45847] px-5 py-3 text-sm font-black text-white transition hover:bg-[#ad493b]"
                >
                    Sí, eliminar cuenta
                </button>

            </div>

        </form>

    </x-modal>

</section>
