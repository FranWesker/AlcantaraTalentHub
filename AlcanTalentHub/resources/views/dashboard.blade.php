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

                    @if(auth()->user()->studentProfile)
                        <h1 class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                            Bienvenido Estudiante: {{ auth()->user()->name }}
                        </h1>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">
                            Has iniciado sesión en tu cuenta de estudiante. Desde aquí podrás configurar tus habilidades y buscar proyectos.
                        </p>
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
