<section>
    <header class="mb-8 border-b border-gray-100 pb-6">
        <h3 class="text-2xl font-extrabold text-gray-900 tracking-tight mb-2">
            {{ __('Actualizar Contraseña') }}
        </h3>

        <p class="text-lg text-gray-600 leading-relaxed">
            {{ __('Asegúrate de que tu cuenta utilice una contraseña larga y aleatoria para mantenerla segura.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-sm font-bold text-gray-900 mb-2 uppercase tracking-wider">
                {{ __('Contraseña Actual') }}
            </label>
            <x-text-input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="block w-full border border-gray-300 rounded-xl shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all sm:text-sm"
                autocomplete="current-password"
            />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-bold text-gray-900 mb-2 uppercase tracking-wider">
                {{ __('Nueva Contraseña') }}
            </label>
            <x-text-input
                id="update_password_password"
                name="password"
                type="password"
                class="block w-full border border-gray-300 rounded-xl shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all sm:text-sm"
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-bold text-gray-900 mb-2 uppercase tracking-wider">
                {{ __('Confirmar Contraseña') }}
            </label>
            <x-text-input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="block w-full border border-gray-300 rounded-xl shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all sm:text-sm"
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-6 border-t border-gray-100">
            <button type="submit" class="inline-flex justify-center items-center px-6 py-2.5 border border-transparent text-sm font-bold rounded-lg text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 shadow-sm transition-all">
                {{ __('Guardar') }}
            </button>

            @if (session('status') === 'password-updated')
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
