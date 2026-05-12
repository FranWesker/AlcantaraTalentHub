<x-app-layout>
    <x-slot name="header">
        {{ __('Mis Habilidades') }}
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 sm:p-10 transition-all hover:shadow-md">

                <section>
                    <header class="mb-8 border-b border-gray-100 pb-6">
                        <h3 class="text-2xl font-extrabold text-gray-900 tracking-tight mb-2">
                            {{ __('Selecciona tus Habilidades') }}
                        </h3>
                        <p class="text-lg text-gray-600 leading-relaxed">
                            {{ __('Selecciona las tecnologías y conocimientos que dominas para destacarlos en tu perfil y aumentar tus posibilidades en las candidaturas.') }}
                        </p>
                    </header>

                    <form method="post" action="{{ route('profile.skills.update') }}">
                        @csrf

                        {{-- Cuadrícula de habilidades rediseñada --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-8">
                            @foreach($skills as $skill)
                                <label class="inline-flex items-center cursor-pointer bg-gray-50 border border-gray-100 rounded-xl p-4 hover:border-blue-300 hover:bg-blue-50 hover:shadow-sm transition-all group">
                                    <input type="checkbox"
                                           name="skills[]"
                                           value="{{ $skill->id }}"
                                           class="rounded border-gray-300 text-blue-700 shadow-sm focus:ring-blue-600 w-5 h-5 transition-all cursor-pointer"
                                           {{ in_array($skill->id, $userSkills) ? 'checked' : '' }}>
                                    <span class="ml-3 text-sm font-semibold text-gray-700 group-hover:text-blue-800 transition-colors">
                                        {{ $skill->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        {{-- Footer del formulario --}}
                        <div class="flex flex-row-reverse items-center justify-start pt-6 border-t border-gray-100 gap-4">

                            <button type="submit" class="inline-flex justify-center items-center px-6 py-2.5 border border-transparent text-sm font-semibold rounded-lg text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 shadow-sm transition-all">
                                {{ __('Guardar Habilidades') }}
                            </button>

                            {{-- Mensaje de éxito animado --}}
                            @if (session('status') === 'skills-updated')
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

            </div>
        </div>
    </div>
</x-app-layout>
