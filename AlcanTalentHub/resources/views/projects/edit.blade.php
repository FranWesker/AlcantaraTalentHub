<x-app-layout>
    <x-slot name="header">
        {{ __('Editar Proyecto: ') }} {{ $project->title }}
    </x-slot>

    <div class="py-10">
        {{-- Contenedor max-w-4xl para que el formulario no se estire demasiado en pantallas grandes --}}
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 sm:p-10 transition-all hover:shadow-md">

                <form method="POST" action="{{ route('projects.update', $project) }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-6">
                        <label for="title" class="block text-sm font-bold text-gray-900 mb-2 uppercase tracking-wider">
                            {{ __('Título del Proyecto') }}
                        </label>
                        <input
                            id="title"
                            type="text"
                            name="title"
                            value="{{ old('title', $project->title) }}"
                            required
                            autofocus
                            class="block w-full border border-gray-300 rounded-xl shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:border-transparent transition-all sm:text-sm"
                        />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block text-sm font-bold text-gray-900 mb-2 uppercase tracking-wider">
                            {{ __('Descripción detallada') }}
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            rows="6"
                            required
                            class="block w-full border border-gray-300 rounded-xl shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:border-transparent transition-all sm:text-sm"
                        >{{ old('description', $project->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="mb-8 bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <label for="is_active" class="inline-flex items-center cursor-pointer">
                            <input
                                id="is_active"
                                type="checkbox"
                                name="is_active"
                                class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500 w-5 h-5 transition-all cursor-pointer"
                                {{ $project->is_active ? 'checked' : '' }}
                            >
                            <span class="ms-3 text-sm font-semibold text-gray-700">
                                {{ __('Proyecto Abierto (visible para estudiantes)') }}
                            </span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end pt-6 border-t border-gray-100 gap-4">
                        <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-800 transition-colors">
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex justify-center items-center px-6 py-2.5 border border-transparent text-sm font-semibold rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-600 shadow-sm transition-all">
                            {{ __('Guardar Cambios') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
