<x-app-layout>
    <x-slot name="header">
        {{ __('Panel Principal') }}
    </x-slot>

    <div class="space-y-6">

        {{-- Mensaje de Estado / Sesión --}}
        @if (session('status'))
            <div class="bg-green-50 border-l-4 border-green-600 p-4 rounded-r-lg shadow-sm" role="alert">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
                </div>
            </div>
        @endif

        {{-- Verificamos si el usuario tiene un perfil de estudiante --}}
        @if(auth()->user()->profile)

            <section aria-labelledby="student-welcome" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition-all hover:shadow-md">
                <div class="p-8 sm:p-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="max-w-2xl">
                        <h2 id="student-welcome" class="text-3xl font-extrabold text-gray-900 mb-3 tracking-tight">
                            Bienvenido Estudiante: <span class="text-blue-700">{{ auth()->user()->name }}</span>
                        </h2>
                        <p class="text-lg text-gray-600 leading-relaxed">
                            Has iniciado sesión en tu cuenta de estudiante. Desde aquí podrás configurar tus habilidades, subir tu currículum y buscar las mejores oportunidades.
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="{{ route('projects.index') }}" class="inline-flex items-center justify-center px-6 py-3.5 border border-transparent text-base font-semibold rounded-lg text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 shadow-sm transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            Explorar Proyectos
                        </a>
                    </div>
                </div>

                {{-- Sección para subir el CV en PDF --}}
                <div class="bg-gray-50 border-t border-gray-100 px-8 py-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Mi Curriculum Vitae</h3>

                    <form action="{{ route('profile.cv.upload') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center gap-4 bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                        @csrf
                        <div class="flex-grow w-full sm:w-auto relative">
                            <label for="cv_file" class="sr-only">Subir CV (Solo PDF)</label>
                            <input
                                type="file"
                                name="cv_file"
                                id="cv_file"
                                accept=".pdf,application/pdf"
                                required
                                class="block w-full text-sm text-gray-600
                                file:mr-4 file:py-2.5 file:px-4
                                file:rounded-lg file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100 transition-all
                                cursor-pointer border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                        </div>
                        <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-2.5 border border-transparent text-sm font-semibold rounded-lg text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 shadow-sm transition-all">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Subir Archivo
                        </button>
                    </form>

                    @error('cv_file')
                        <p class="mt-2 text-sm font-medium text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </section>

        @else

            <section aria-labelledby="company-welcome" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8 transition-all hover:shadow-md">
                <div class="p-8 sm:p-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="max-w-2xl">
                        <h2 id="company-welcome" class="text-3xl font-extrabold text-gray-900 mb-3 tracking-tight">
                            Bienvenida Empresa: <span class="text-emerald-600">{{ auth()->user()->name }}</span>
                        </h2>
                        <p class="text-lg text-gray-600 leading-relaxed">
                            Has iniciado sesión en tu cuenta de empresa. Gestiona tus ofertas, revisa a los postulantes y conecta con el talento local desde este panel.
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="{{ route('projects.create') }}" class="inline-flex items-center justify-center px-6 py-3.5 border border-transparent text-base font-semibold rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-600 shadow-sm transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Añadir Proyecto
                        </a>
                    </div>
                </div>
            </section>

            <div>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 tracking-tight">Mis Proyectos Publicados</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse(auth()->user()->publishedProjects as $project)
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow flex flex-col h-full">

                            {{-- Cabecera y descripción de la tarjeta --}}
                            <div>
                                <div class="flex justify-between items-start gap-4 mb-3">
                                    <h4 class="text-xl font-bold text-gray-900 line-clamp-2 leading-tight">
                                        {{ $project->title }}
                                    </h4>
                                    @if($project->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">
                                            Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                                            Cerrado
                                        </span>
                                    @endif
                                </div>
                                <p class="text-gray-600 text-sm line-clamp-3 mb-4">
                                    {{ Str::limit($project->description, 100) }}
                                </p>
                            </div>

                            {{-- Footer de la tarjeta con acciones --}}
                            <div class="mt-auto pt-5 border-t border-gray-100">
                                <div class="flex items-center text-sm text-gray-500 mb-4">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Publicado: {{ $project->created_at->format('d/m/Y') }}
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    {{-- Postulantes --}}
                                    <a href="{{ route('projects.applicants', $project->id) }}" class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-gray-200 text-sm font-semibold rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all">
                                        <svg class="w-4 h-4 mr-1.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        Postulantes
                                    </a>

                                    {{-- Editar --}}
                                    <a href="{{ route('projects.edit', $project) }}" class="inline-flex justify-center items-center px-3 py-2 border border-gray-200 text-sm font-semibold rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                                        Editar
                                    </a>

                                    {{-- Eliminar --}}
                                    <form method="POST" action="{{ route('projects.destroy', $project) }}" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este proyecto? Esta acción no se puede deshacer.');" class="inline-flex">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex justify-center items-center px-3 py-2 border border-red-200 text-sm font-semibold rounded-lg text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600 transition-all">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        {{-- Estado Vacío (Sin proyectos) --}}
                        <div class="col-span-full bg-white rounded-2xl p-10 text-center border border-gray-100 shadow-sm flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <h4 class="text-xl font-bold text-gray-900 mb-2">Aún no has publicado ningún proyecto</h4>
                            <p class="text-gray-500 max-w-md mx-auto mb-6">El primer paso para encontrar talento es publicar una oferta. ¡Anímate a crear el primero!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
