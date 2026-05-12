<section class="space-y-6">
    <header class="mb-8 border-b border-red-100 pb-6">
        <h3 class="text-2xl font-extrabold text-red-600 tracking-tight mb-2">
            {{ __('Eliminar Cuenta') }}
        </h3>

        <p class="text-lg text-gray-600 leading-relaxed">
            {{ __('Una vez que se elimine tu cuenta, todos sus recursos y datos se eliminarán permanentemente. Antes de eliminar tu cuenta, por favor descarga cualquier dato o información que desees conservar.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-6 py-2.5 rounded-lg font-semibold transition-all shadow-sm hover:shadow-md"
    >
        {{ __('Eliminar Cuenta') }}
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8 sm:p-10">
            @csrf
            @method('delete')

            <h3 class="text-2xl font-extrabold text-gray-900 tracking-tight mb-3">
                {{ __('¿Estás seguro de que quieres eliminar tu cuenta?') }}
            </h3>

            <p class="text-base text-gray-600 leading-relaxed mb-6">
                {{ __('Una vez que tu cuenta sea eliminada, todos sus recursos y datos se borrarán de forma permanente. Por favor, introduce tu contraseña para confirmar que deseas eliminar tu cuenta de forma definitiva.') }}
            </p>

            <div class="mb-8">
                <x-input-label for="password" value="{{ __('Contraseña') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="block w-full border border-gray-300 rounded-xl shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent transition-all sm:text-sm"
                    placeholder="{{ __('Contraseña') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end gap-4 border-t border-gray-100 pt-6">
                <x-secondary-button x-on:click="$dispatch('close')" class="rounded-lg shadow-sm transition-all hover:bg-gray-50 border-gray-200">
                    {{ __('Cancelar') }}
                </x-secondary-button>

                <x-danger-button class="rounded-lg shadow-sm transition-all hover:bg-red-700">
                    {{ __('Eliminar Cuenta') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
