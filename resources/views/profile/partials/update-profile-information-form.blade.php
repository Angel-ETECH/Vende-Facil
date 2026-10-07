<section>

    <header>

        <div class="flex items-start gap-4">

            <div
                class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-2xl bg-[#fff0e4] text-[#ff6b57]"
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
                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0"
                    />
                </svg>
            </div>


            <div>

                <h2
                    class="text-xl font-black tracking-[-.025em] text-[#35272a]"
                >
                    Información del perfil
                </h2>

                <p
                    class="mt-1 text-sm leading-6 text-[#806d70]"
                >
                    Actualiza tu nombre y correo electrónico.
                </p>

            </div>

        </div>

    </header>


    <form
        id="send-verification"
        method="post"
        action="{{ route('verification.send') }}"
    >
        @csrf
    </form>


    <form
        method="post"
        action="{{ route('profile.update') }}"
        class="mt-7 max-w-2xl space-y-5"
    >

        @csrf
        @method('patch')


        {{-- NOMBRE --}}
        <div>

            <label
                for="name"
                class="mb-2 block text-sm font-black text-[#514043]"
            >
                Nombre
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
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0"
                        />
                    </svg>
                </div>


                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name', $user->name) }}"
                    required
                    autofocus
                    autocomplete="name"
                    class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] py-3.5 pl-12 pr-4 text-[#3d3032] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                >

            </div>

            @error('name')
                <p class="mt-2 text-sm font-semibold text-[#c34d3c]">
                    {{ $message }}
                </p>
            @enderror

        </div>


        {{-- EMAIL --}}
        <div>

            <label
                for="email"
                class="mb-2 block text-sm font-black text-[#514043]"
            >
                Correo electrónico
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
                            d="M21.75 6.75v10.5A2.25 2.25 0 0 1 19.5 19.5h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0-8.69 5.79a1.9 1.9 0 0 1-2.12 0L2.25 6.75"
                        />
                    </svg>
                </div>


                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email', $user->email) }}"
                    required
                    autocomplete="username"
                    class="w-full rounded-[16px] border border-[#e4d6cd] bg-[#fffdf9] py-3.5 pl-12 pr-4 text-[#3d3032] focus:border-[#ff8b78] focus:ring-[#ff8b78]"
                >

            </div>

            @error('email')
                <p class="mt-2 text-sm font-semibold text-[#c34d3c]">
                    {{ $message }}
                </p>
            @enderror

        </div>


        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())

            <div
                class="rounded-[18px] border border-[#f0dfb0] bg-[#fff8dd] px-4 py-3 text-sm text-[#85671e]"
            >

                <p>
                    Tu correo electrónico aún no ha sido verificado.
                </p>

                <button
                    form="send-verification"
                    class="mt-2 font-black text-[#ff604c] hover:text-[#db4937]"
                >
                    Reenviar correo de verificación
                </button>

            </div>

        @endif


        <div
            class="flex items-center gap-4 pt-2"
        >

            <button
                type="submit"
                class="rounded-[15px] bg-[#ff6b57] px-6 py-3.5 text-sm font-black text-white transition hover:bg-[#ed5542]"
            >
                Guardar cambios
            </button>


            @if (session('status') === 'profile-updated')

                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-sm font-bold text-[#4e8b79]"
                >
                    Cambios guardados.
                </p>

            @endif

        </div>

    </form>

</section>