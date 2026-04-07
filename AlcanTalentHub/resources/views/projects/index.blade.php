<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Directorio de Proyectos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white">Proyectos Disponibles</h3>
                        <p class="text-gray-600 dark:text-gray-400">Descubre oportunidades y postúlate a los proyectos de nuestras empresas colaboradoras.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($projects as $project)
                            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-600 flex flex-col h-full hover:shadow-md transition-shadow duration-300">

                                <div class="flex-grow">
                                    <div class="flex justify-between items-start mb-4">
                                        <h4 class="text-xl font-bold text-indigo-600 dark:text-indigo-400 leading-tight">
                                            {{ $project->title }}
                                        </h4>
                                    </div>

                                    <div class="mb-4">
                                        <span class="inline-flex items-center text-sm font-medium text-gray-500 dark:text-gray-300">
                                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"></path></svg>
                                            {{ $project->company->name ?? 'Empresa Confidencial' }}
                                        </span>
                                    </div>

                                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 line-clamp-3">
                                        {{ $project->description }}
                                    </p>
                                </div>
                                <div class="mt-4 flex justify-between items-center">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                            Publicado: {{ $project->created_at->diffForHumans() }}
                                        </span>
                                    <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 rounded-md font-semibold text-xs text-white uppercase hover:bg-indigo-700">
                                        Ver más
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-12 px-4 text-center bg-gray-50 dark:bg-gray-800/50 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-200">No hay proyectos activos</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Las empresas aún no han publicado proyectos disponibles.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-8">
                        {{ $projects->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
