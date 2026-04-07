<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detalles del Proyecto</h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-2xl font-bold mb-4">{{ $project->title }}</h3>
            <p class="text-gray-700 mb-6">{{ $project->description }}</p>

            <p class="mb-6"><strong>Empresa:</strong> {{ $project->company->name ?? 'N/A' }}</p>

            @if(auth()->check() && auth()->user()->role === 'estudiante')
                @if($hasApplied)
                    <button disabled class="px-4 py-2 bg-gray-400 text-white rounded cursor-not-allowed">
                        Ya te has postulado (Estado: {{ auth()->user()->applications()->where('project_id', $project->id)->first()->pivot->status }})
                    </button>
                @else
                    <form action="{{ route('applications.store', $project) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded">
                            Unirse al proyecto
                        </button>
                    </form>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
