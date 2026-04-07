<x-app-layout>
    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold mb-6">Mis Proyectos y Solicitudes</h2>

        @forelse($projects as $project)
            <div class="bg-white shadow-sm sm:rounded-lg mb-6 p-6">
                <h3 class="text-lg font-bold mb-4">Proyecto: {{ $project->title }}</h3>

                @if($project->applicants->isEmpty())
                    <p class="text-gray-500">No hay postulantes aún.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estudiante</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($project->applicants as $student)
                                <tr>
                                    <td class="px-6 py-4">{{ $student->name }}</td>
                                    <td class="px-6 py-4">
                                        {{ ucfirst($student->pivot->status) }}
                                    </td>
                                    <td class="px-6 py-4 flex gap-2">
                                        @if($student->pivot->status === 'pending')
                                            <form action="{{ route('applications.updateStatus', [$project->id, $student->id]) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="accepted">
                                                <button type="submit" class="text-green-600 font-bold hover:underline">Aceptar</button>
                                            </form>
                                            <form action="{{ route('applications.updateStatus', [$project->id, $student->id]) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="text-red-600 font-bold hover:underline">Rechazar</button>
                                            </form>
                                        @else
                                            <span class="text-gray-400">Resuelto</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        @empty
            <p>No has publicado proyectos.</p>
        @endforelse
    </div>
</x-app-layout>
