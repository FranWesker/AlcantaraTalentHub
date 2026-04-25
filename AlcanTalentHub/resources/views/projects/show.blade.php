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

            {{-- PANEL DE ADMINISTRACIÓN DE LA EMPRESA // Esto lo tengo que eliminar --}}
            @if($isOwner)

                {{-- Sección: Postulaciones Pendientes --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 border-b border-gray-200">
                        <h4 class="text-xl font-semibold mb-4 text-orange-600">Postulaciones Pendientes</h4>

                        @if($pendingApplicants->isEmpty())
                            <p class="text-gray-500">No hay postulaciones pendientes en este momento.</p>
                        @else
                            <ul class="divide-y divide-gray-200">
                                @foreach($pendingApplicants as $student)
                                    <li class="py-4 flex justify-between items-center">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $student->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $student->email }}</p>
                                        </div>
                                        <div class="flex space-x-3 items-center">
                                            {{-- Enlace al CV --}}
                                            @if($student->profile && $student->profile->cv_path)
                                                <a href="{{ Storage::url($student->profile->cv_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                    Ver CV
                                                </a>
                                            @else
                                                <span class="text-sm text-gray-400">Sin CV</span>
                                            @endif

                                            {{-- Botón de Aceptar --}}
                                            <form action="{{ route('applications.accept', [$project, $student]) }}" method="POST" onsubmit="return confirm('¿Estás seguro de aceptar a este estudiante?');">
                                                @csrf
                                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-sm font-bold py-1 px-3 rounded">
                                                    Aceptar
                                                </button>
                                            </form>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                {{-- Sección: Estudiantes Aceptados (Miembros del equipo) --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h4 class="text-xl font-semibold mb-4 text-green-600">Miembros del Equipo (Aceptados)</h4>

                        @if($acceptedApplicants->isEmpty())
                            <p class="text-gray-500">Aún no has aceptado a ningún estudiante.</p>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($acceptedApplicants as $student)
                                    <div class="border rounded-lg p-4 shadow-sm">
                                        <p class="font-bold text-gray-800">{{ $student->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $student->email }}</p>
                                        <span class="inline-block mt-2 px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                            Aceptado el {{ $student->pivot->updated_at->format('d/m/Y') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
