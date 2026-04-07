<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Panel Principal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ session('status') }}
                        </div>
                    @endif
                    {{-- Verificamos si el usuario tiene un perfil de estudiante --}}
                    @if(auth()->user()->profile)
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div>
                                <h1 class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                    Bienvenido Estudiante: {{ auth()->user()->name }}
                                </h1>
                                <p class="mt-2 text-gray-600 dark:text-gray-400">
                                    Has iniciado sesión en tu cuenta de estudiante. Desde aquí podrás configurar tus habilidades y buscar proyectos.
                                </p>
                            </div>
                            {{-- Botón destacado para ver todos los proyectos --}}
                            <div class="flex-shrink-0">
                                <a href="{{ route('projects.index') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-lg font-bold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    Explorar Proyectos
                                </a>
                            </div>
                        </div>
                        {{-- Sección para subir el CV en PDF --}}
                        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Mi Curriculum Vitae</h2>

                            <form action="{{ route('profile.cv.upload') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center gap-4 bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                                @csrf
                                <div class="flex-grow w-full sm:w-auto">
                                    <label for="cv_file" class="sr-only">Subir CV (Solo PDF)</label>
                                    <input
                                        type="file"
                                        name="cv_file"
                                        id="cv_file"
                                        accept=".pdf,application/pdf"
                                        required
                                        class="block w-full text-sm text-gray-500 dark:text-gray-400
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-indigo-100 file:text-indigo-700
                                        hover:file:bg-indigo-200
                                        dark:file:bg-indigo-900/50 dark:file:text-indigo-300 dark:hover:file:bg-indigo-800/50
                                        cursor-pointer border border-gray-300 dark:border-gray-500 rounded-md bg-white dark:bg-gray-800">
                                </div>
                                <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    Subir Archivo
                                </button>
                            </form>
                            @error('cv_file')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                    {{-- Lógica para empresas --}}
                    @else
                        <div class="flex justify-between items-center">
                            <div>
                                <h1 class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                                    Bienvenida Empresa: {{ auth()->user()->name }}
                                </h1>
                                <p class="mt-2 text-gray-600 dark:text-gray-400">
                                    Has iniciado sesión como cuenta de empresa.
                                </p>
                            </div>

                            <div>
                                <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    + Añadir Proyecto
                                </a>
                            </div>
                        </div>

                        <div class="mt-10 border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Mis Proyectos Publicados</h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @forelse(auth()->user()->publishedProjects as $project)
                                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow border border-gray-200 dark:border-gray-600 flex flex-col justify-between">
                                        <div>
                                            <div class="flex justify-between items-start">
                                                <h3 class="text-lg font-bold text-emerald-600 dark:text-emerald-400">{{ $project->title }}</h3>
                                                @if($project->is_active)
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Cerrado</span>
                                                @endif
                                            </div>
                                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                                {{ Str::limit($project->description, 100) }}
                                            </p>
                                        </div>

                                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600 flex justify-between items-center">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                Publicado: {{ $project->created_at->format('d/m/Y') }}
                                            </span>

                                            <div class="flex space-x-2">
                                                <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    Editar
                                                </a>

                                                <form method="POST" action="{{ route('projects.destroy', $project) }}" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este proyecto? Esta acción no se puede deshacer.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-full p-4 bg-yellow-50 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded-md border border-yellow-200 dark:border-yellow-800">
                                        Aún no has publicado ningún proyecto. ¡Anímate a crear el primero!
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
