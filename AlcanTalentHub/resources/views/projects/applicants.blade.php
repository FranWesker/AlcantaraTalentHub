<x-app-layout>
    <x-slot name="header">
        {{ __('Postulantes para: ') }} {{ $project->title }}
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 sm:p-10 transition-all hover:shadow-md">

                {{-- Cabecera con título y botón de volver --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 border-b border-gray-100 pb-6 gap-4">
                    <h3 class="text-2xl font-extrabold text-gray-900 tracking-tight">Lista de Alumnos</h3>
                    <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-800 transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Volver al panel
                    </a>
                </div>

                @if($applicants->isEmpty())
                    {{-- Estado vacío rediseñado --}}
                    <div class="bg-gray-50 rounded-2xl p-10 text-center border border-gray-100">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-gray-100">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-2">Aún no hay postulantes</h4>
                        <p class="text-gray-500 max-w-md mx-auto">Nadie se ha postulado para este proyecto todavía. Las nuevas candidaturas aparecerán aquí.</p>
                    </div>
                @else
                    {{-- Tabla de postulantes --}}
                    <div class="overflow-hidden rounded-xl border border-gray-200 shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-600">
                                <thead class="text-xs text-gray-900 uppercase bg-gray-50 border-b border-gray-200 tracking-wider">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 font-bold">Nombre del Alumno</th>
                                        <th scope="col" class="px-6 py-4 font-bold">Estado Actual</th>
                                        <th scope="col" class="px-6 py-4 font-bold">Habilidades</th>
                                        <th scope="col" class="px-6 py-4 font-bold text-center">Currículum</th>
                                        <th scope="col" class="px-6 py-4 font-bold text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($applicants as $applicant)
                                        <tr class="bg-white border-b border-gray-100 hover:bg-gray-50 transition-colors last:border-b-0">

                                            <td class="px-6 py-4">
                                                <div class="font-bold text-gray-900">{{ $applicant->name }}</div>
                                                <div class="text-gray-500 text-xs mt-0.5">{{ $applicant->email }}</div>
                                            </td>

                                            <td class="px-6 py-4">
                                                @if($applicant->pivot->status === 'pending')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-50 text-yellow-700 border border-yellow-200">
                                                        Pendiente
                                                    </span>
                                                @elseif($applicant->pivot->status === 'accepted')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                        Aceptado
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                                        Rechazado
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="px-6 py-4">
                                                @if($applicant->skills && $applicant->skills->isNotEmpty())
                                                    <div class="flex flex-wrap gap-1.5">
                                                        @foreach($applicant->skills as $skill)
                                                            <span class="px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                                                {{ $skill->name }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-gray-400 italic text-xs">Sin especificar</span>
                                                @endif
                                            </td>

                                            <td class="px-6 py-4 text-center">
                                                @if($applicant->profile && $applicant->profile->cv_pdf_path)
                                                    <a href="{{ Storage::url($applicant->profile->cv_pdf_path) }}"
                                                       target="_blank"
                                                       class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors shadow-sm">
                                                       <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                        Ver CV
                                                    </a>
                                                @else
                                                    <span class="text-gray-400 italic text-xs">Sin CV</span>
                                                @endif
                                            </td>

                                            <td class="px-6 py-4 text-right">
                                                <div class="flex justify-end items-center gap-2">
                                                    @if($applicant->pivot->status !== 'accepted')
                                                        <form action="{{ route('applications.updateStatus', ['project' => $project->id, 'student_id' => $applicant->id]) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="accepted">
                                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-semibold hover:bg-emerald-700 transition-colors shadow-sm">
                                                                Aceptar
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if($applicant->pivot->status !== 'rejected')
                                                        <form action="{{ route('applications.updateStatus', ['project' => $project->id, 'student_id' => $applicant->id]) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas rechazar/echar a este alumno?');">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="rejected">
                                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-white border border-red-200 text-red-600 rounded-lg text-xs font-semibold hover:bg-red-50 hover:border-red-300 transition-colors shadow-sm">
                                                                Rechazar
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
