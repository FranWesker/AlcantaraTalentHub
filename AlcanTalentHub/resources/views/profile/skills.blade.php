<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Habilidades') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-2xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Selecciona tus Habilidades') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Selecciona las tecnologías y conocimientos que dominas para destacarlos en tu perfil.') }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('profile.skills.update') }}" class="mt-6 space-y-6">
                            @csrf

                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($skills as $skill)
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox"
                                               name="skills[]"
                                               value="{{ $skill->id }}"
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                               {{ in_array($skill->id, $userSkills) ? 'checked' : '' }}>
                                        <span class="ml-2 text-gray-700">{{ $skill->name }}</span>
                                    </label>
                                @endforeach
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Guardar Habilidades') }}</x-primary-button>

                                @if (session('status') === 'skills-updated')
                                    <p
                                        x-data="{ show: true }"
                                        x-show="show"
                                        x-transition
                                        x-init="setTimeout(() => show = false, 2000)"
                                        class="text-sm text-green-600"
                                    >{{ __('Guardado correctamente.') }}</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
