<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $project->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Detalles del Proyecto --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-4">{{ $project->title }}</h3>
                    <p class="mb-4">{{ $project->description }}</p>

                    {{-- Botón para postularse (solo si es estudiante) --}}
                    @if(auth()->user()->isStudent())
                        <form action="{{ route('applications.store', $project) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Postularme a este proyecto
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
