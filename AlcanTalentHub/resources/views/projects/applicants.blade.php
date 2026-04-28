<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Postulantes para: {{ $project->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Lista de Alumnos</h3>

                @if($applicants->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400">Aún no hay postulantes para este proyecto.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Nombre del Alumno</th>
                                    <th scope="col" class="px-6 py-3">Estado Actual</th>
                                    <th scope="col" class="px-6 py-3">Habilidades</th>
                                    <th scope="col" class="px-6 py-3 text-center">Currículum</th>
                                    <th scope="col" class="px-6 py-3 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($applicants as $applicant)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">

                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                            {{ $applicant->name }} ({{ $applicant->email }})
                                        </td>

                                        <td class="px-6 py-4">
                                            @if($applicant->pivot->status === 'pending')
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">Pendiente</span>
                                            @elseif($applicant->pivot->status === 'accepted')
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Aceptado</span>
                                            @else
                                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">Rechazado</span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4">
                                            @if($applicant->skills && $applicant->skills->isNotEmpty())
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($applicant->skills as $skill)
                                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300">
                                                            {{ $skill->name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500 italic text-xs">Sin habilidades especificadas</span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            @if($applicant->profile && $applicant->profile->cv_pdf_path)
                                                <a href="{{ Storage::url($applicant->profile->cv_pdf_path) }}"
                                                   target="_blank"
                                                   class="inline-block px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-xs font-semibold shadow-sm transition duration-150 ease-in-out">
                                                    Ver CV
                                                </a>
                                            @else
                                                <span class="text-gray-400 italic text-xs">Sin CV</span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 text-right flex justify-end gap-2">

                                            @if($applicant->pivot->status !== 'accepted')
                                                <form action="{{ route('applications.updateStatus', ['project' => $project->id, 'student_id' => $applicant->id]) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="accepted">
                                                    <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-xs font-semibold shadow-sm transition duration-150 ease-in-out">
                                                        Aceptar
                                                    </button>
                                                </form>
                                            @endif

                                            @if($applicant->pivot->status !== 'rejected')
                                                <form action="{{ route('applications.updateStatus', ['project' => $project->id, 'student_id' => $applicant->id]) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas rechazar/echar a este alumno?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-xs font-semibold shadow-sm transition duration-150 ease-in-out">
                                                        Rechazar
                                                    </button>
                                                </form>
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <div class="mt-6">
                    <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-900 transition duration-150 ease-in-out">&larr; Volver al panel</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
