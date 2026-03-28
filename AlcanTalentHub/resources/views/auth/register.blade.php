<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" x-data="{ account_type: 'student' }">
        @csrf

        <div class="mb-6 flex gap-4 p-1 bg-gray-100 dark:bg-gray-800 rounded-lg">
            <label class="flex-1 cursor-pointer">
                <input type="radio" name="account_type" value="student" x-model="account_type" class="peer sr-only">
                <div class="text-center py-2 rounded-md transition-all peer-checked:bg-white dark:peer-checked:bg-gray-900 peer-checked:shadow-sm peer-checked:text-indigo-600 dark:peer-checked:text-indigo-400 font-medium text-gray-500">
                    Soy un Estudiante
                </div>
            </label>
            <label class="flex-1 cursor-pointer">
                <input type="radio" name="account_type" value="company" x-model="account_type" class="peer sr-only">
                <div class="text-center py-2 rounded-md transition-all peer-checked:bg-white dark:peer-checked:bg-gray-900 peer-checked:shadow-sm peer-checked:text-indigo-600 dark:peer-checked:text-indigo-400 font-medium text-gray-500">
                    Soy una Empresa
                </div>
            </label>
        </div>
        <x-input-error :messages="$errors->get('account_type')" class="mt-2" />

        <div>
            <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300" x-text="account_type === 'student' ? 'Nombre Completo' : 'Nombre de la Empresa'"></label>
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div x-show="account_type === 'student'" class="mt-4 space-y-4 p-4 border border-gray-200 dark:border-gray-700 rounded-md bg-gray-50 dark:bg-gray-800/50">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Redes Profesionales (Opcional)</h3>
            <div>
                <x-input-label for="github_url" value="URL de GitHub" />
                <x-text-input id="github_url" class="block mt-1 w-full" type="url" name="github_url" :value="old('github_url')" placeholder="https://github.com/..." />
                <x-input-error :messages="$errors->get('github_url')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="linkedin_url" value="URL de LinkedIn" />
                <x-text-input id="linkedin_url" class="block mt-1 w-full" type="url" name="linkedin_url" :value="old('linkedin_url')" placeholder="https://linkedin.com/in/..." />
                <x-input-error :messages="$errors->get('linkedin_url')" class="mt-2" />
            </div>
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md" href="{{ route('login') }}">
                {{ __('¿Ya estás registrado?') }}
            </a>
            <x-primary-button class="ms-4">
                {{ __('Registrarse') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
