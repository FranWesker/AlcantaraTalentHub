<section>
    <header class="mb-8 border-b border-gray-100 pb-6">
        <h3 class="text-2xl font-extrabold text-gray-900 tracking-tight mb-2">
            {{ __('Información del Perfil') }}
        </h3>

        <p class="text-lg text-gray-600 leading-relaxed">
            {{ __('Actualiza la información del perfil y la dirección de correo electrónico de tu cuenta.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="mb-6">
            <label for="name" class="block text-sm font-bold text-gray-900 mb-2 uppercase tracking-wider">
                {{ __('Nombre') }}
            </label>
            <x-text-input
                id="name"
                name="name"
                type="text"
                class="block w-full border border-gray-300 rounded-xl shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all sm:text-sm"
                :value="old('name', $user->name)"
                required
                autofocus
                autocomplete="name"
            />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="mb-6">
            <label for="email" class="block text-sm font-bold text-gray-900 mb-2 uppercase tracking-wider">
                {{ __('Correo Electrónico') }}
            </label>
            <x-text-input
                id="email"
                name="email"
                type="email"
                class="block w-full border border-gray-300 rounded-xl shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all sm:text-sm"
                :value="old('email', $user->email)"
                required
                autocomplete="username"
            />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-4 p-4 bg-yellow-50 rounded-xl border border-yellow-100 shadow-sm">
                    <p class="text-sm font-semibold text-yellow-800">
                        {{ __('Tu dirección de correo electrónico no está verificada.') }}

                        <button form="send-verification" class="underline hover:text-yellow-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-600 rounded-md transition-colors ml-1">
                            {{ __('Haz clic aquí para reenviar el correo de verificación.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-3 font-semibold text-sm text-emerald-600 flex items-center bg-emerald-50 px-3 py-2 rounded-lg border border-emerald-100">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ __('Se ha enviado un nuevo enlace de verificación a tu correo electrónico.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Campos extra solo para estudiantes --}}
        @if ($user->profile)
            <div class="mb-6">
                <label for="github_url" class="block text-sm font-bold text-gray-900 mb-2 uppercase tracking-wider">
                    {{ __('URL de GitHub') }}
                </label>
                <x-text-input
                    id="github_url"
                    name="github_url"
                    type="url"
                    class="block w-full border border-gray-300 rounded-xl shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all sm:text-sm"
                    :value="old('github_url', $user->profile->github_url)"
                    placeholder="https://github.com/tu-usuario"
                    autocomplete="url"
                />
                <x-input-error class="mt-2" :messages="$errors->get('github_url')" />
            </div>

            <div class="mb-6">
                <label for="linkedin_url" class="block text-sm font-bold text-gray-900 mb-2 uppercase tracking-wider">
                    {{ __('URL de LinkedIn') }}
                </label>
                <x-text-input
                    id="linkedin_url"
                    name="linkedin_url"
                    type="url"
                    class="block w-full border border-gray-300 rounded-xl shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all sm:text-sm"
                    :value="old('linkedin_url', $user->profile->linkedin_url)"
                    placeholder="https://linkedin.com/in/tu-usuario"
                    autocomplete="url"
                />
                <x-input-error class="mt-2" :messages="$errors->get('linkedin_url')" />
            </div>
        @endif

        <div class="flex items-center gap-4 pt-6 border-t border-gray-100">
            <button type="submit" class="inline-flex justify-center items-center px-6 py-2.5 border border-transparent text-sm font-bold rounded-lg text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 shadow-sm transition-all">
                {{ __('Guardar') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm font-semibold text-emerald-600 flex items-center bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100"
                >
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ __('Guardado correctamente.') }}
                </p>
            @endif
        </div>
    </form>
</section>
