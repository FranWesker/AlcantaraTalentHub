<x-app-layout>
    <x-slot name="header">
        {{ __('Detalles del Proyecto') }}
    </x-slot>

    <div class="py-10">
        {{-- Limitamos el ancho a 4xl para que la lectura sea más cómoda en pantallas grandes --}}
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Detalles del Proyecto --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition-all hover:shadow-md">
                <div class="p-8 sm:p-10">

                    {{-- Cabecera del proyecto --}}
                    <div class="mb-8 border-b border-gray-100 pb-6">
                        <h3 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                            {{ $project->title }}
                        </h3>
                    </div>

                    {{-- Descripción --}}
                    <div class="text-lg text-gray-600 leading-relaxed mb-8 whitespace-pre-wrap">
                        {{ $project->description }}
                    </div>

                    {{-- Botón para postularse (solo si es estudiante) --}}
                    @if(auth()->user()->isStudent())
                        <div class="pt-6 border-t border-gray-100">
                            <form action="{{ route('applications.store', $project) }}" method="POST" class="flex justify-end">
                                @csrf
                                <button type="submit" class="inline-flex items-center justify-center px-6 py-3.5 border border-transparent text-base font-semibold rounded-lg text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 shadow-sm transition-all">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    Postularme a este proyecto
                                </button>
                            </form>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
