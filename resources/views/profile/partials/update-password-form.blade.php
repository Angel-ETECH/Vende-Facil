<section>

    <header>

        <div class="flex items-start gap-4">

            <div
                class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-2xl bg-[#e8f5f0] text-[#4e8b79]"
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
                        d="M16.5 10.5V6.75a4.5 4.5 0 0 0-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0 1 19.5 12.75v6A2.25 2.25 0 0 1 17.25 21H6.75A2.25 2.25 0 0 1 4.5 18.75v-6A2.25 2.25 0 0 1 6.75 10.5Z"
                    />
                </svg>
            </div>


            <div>

                <h2
                    class="text-xl font-black tracking-[-.025em] text-[#35272a]"
                >
                    Cambiar contraseña
                </h2>

                <p
                    class="mt-1 text-sm leading-6 text-[#806d70]"
                >
                    Utiliza una contraseña segura para proteger tu cuenta.
                </p>

            </div>

        </div>

    </header>


    <div
        class="mt-6 rounded-[18px] border border-[#d7eee6] bg-[#f0faf6] px-4 py-3"
    >
        <p class="text-sm font-semibold leading-6 text-[#4e7d6e]">
            Te recomendamos usar una contraseña distinta a la de tu correo o redes sociales.
        </p>
    </div>


    <form
        method="post"
        action="{{ route('password.update') }}"
        class="mt-7 max-w-2xl space-y-5"
    >

        @csrf
        @method('put')


        {{-- ACTUAL --}}
        <div>

            <label
                for="update_password_current_password"
                class="mb-2 block text-sm font-black text-[#514043]"
            >
                Contraseña actual
            </label>

            <input
                id="update_password_current_password"
                name="current_password"
                type="password"
                autocomplete="current-password"
                class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3.5 text-[#3d3032] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
            >

            @if($errors->updatePassword->has('current_password'))
                <p class="mt-2 text-sm font-semibold text-[#c34d3c]">
                    {{ $errors->updatePassword->first('current_password') }}
                </p>
            @endif

        </div>


        {{-- NUEVA --}}
        <div>

            <label
                for="update_password_password"
                class="mb-2 block text-sm font-black text-[#514043]"
            >
                Nueva contraseña
            </label>

            <input
                id="update_password_password"
                name="password"
                type="password"
                autocomplete="new-password"
                class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3.5 text-[#3d3032] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
            >

            @if($errors->updatePassword->has('password'))
                <p class="mt-2 text-sm font-semibold text-[#c34d3c]">
                    {{ $errors->updatePassword->first('password') }}
                </p>
            @endif

        </div>


        {{-- CONFIRMAR --}}
        <div>

            <label
                for="update_password_password_confirmation"
                class="mb-2 block text-sm font-black text-[#514043]"
            >
                Confirmar nueva contraseña
            </label>

            <input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                autocomplete="new-password"
                class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] px-4 py-3.5 text-[#3d3032] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
            >

            @if($errors->updatePassword->has('password_confirmation'))
                <p class="mt-2 text-sm font-semibold text-[#c34d3c]">
                    {{ $errors->updatePassword->first('password_confirmation') }}
                </p>
            @endif

        </div>


        <div
            class="flex items-center gap-4 pt-2"
        >

            <button
                type="submit"
                class="rounded-[15px] bg-[#442d35] px-6 py-3.5 text-sm font-black text-white transition hover:bg-[#ff6b57]"
            >
                Actualizar contraseña
            </button>


            @if (session('status') === 'password-updated')

                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-sm font-bold text-[#4e8b79]"
                >
                    Contraseña actualizada.
                </p>

            @endif

        </div>

    </form>

</section>
