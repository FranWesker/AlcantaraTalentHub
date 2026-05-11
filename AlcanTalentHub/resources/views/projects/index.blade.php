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

                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filtros rápidos por tecnología:</p>
                        <div id="filterButtonsContainer" class="flex flex-wrap gap-2">
                            @foreach(['PHP', 'JavaScript', 'Java','HTML', 'Python', 'Diseño','C#'] as $tech)
                                <button
                                    type="button"
                                    class="filter-btn px-4 py-2 rounded-full border border-indigo-600 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400 hover:bg-indigo-600 hover:text-white dark:hover:bg-indigo-400 dark:hover:text-gray-900 transition-all duration-200 text-xs font-bold uppercase tracking-wider"
                                    data-value="{{ $tech }}"
                                >
                                    {{ $tech }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-8">
                        <label for="searchInput" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Buscador Avanzado</label>
                        <input
                            type="text"
                            id="searchInput"
                            placeholder="Busca proyectos por título o descripción..."
                            class="mt-1 w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm p-2 focus:ring focus:ring-indigo-200 focus:border-indigo-500"
                        >
                    </div>

                    <div id="projectsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-200">No hay proyectos activos</h3>
                            </div>
                        @endforelse
                    </div>

                    <div id="paginationContainer" class="mt-8">
                        {{ $projects->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const projectsContainer = document.getElementById('projectsContainer');
            const paginationContainer = document.getElementById('paginationContainer');
            const filterButtons = document.querySelectorAll('.filter-btn');

            // Guardamos el estado inicial para la lógica de limpieza rápida
            const initialProjectsHTML = projectsContainer.innerHTML;
            const initialPaginationDisplay = paginationContainer ? paginationContainer.style.display : 'block';

            /**
             * Función centralizada de búsqueda (Reutilizable)
             */
            function performSearch(query) {
                const trimmedQuery = query.trim();

                // Lógica de limpieza: Si el query está vacío, restauramos la vista original
                if (trimmedQuery === '') {
                    projectsContainer.innerHTML = initialProjectsHTML;
                    if (paginationContainer) paginationContainer.style.display = initialPaginationDisplay;
                    updateActiveButton(''); // Limpiar estilos de botones
                    return;
                }

                // Ocultamos la paginación durante la búsqueda AJAX
                if (paginationContainer) paginationContainer.style.display = 'none';

                const urlBusqueda = `{{ route('projects.search') }}?query=${encodeURIComponent(trimmedQuery)}`;

                fetch(urlBusqueda, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest', // Indicamos que es una petición AJAX
                        'Accept': 'application/json'           // Esperamos JSON como respuesta
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error("Error en la respuesta de la API");
                    return response.json();
                })
                .then(data => {
                    projectsContainer.innerHTML = '';

                    if (data.length === 0) {
                        projectsContainer.innerHTML = `
                            <div class="col-span-full py-12 px-4 text-center bg-gray-50 dark:bg-gray-800/50 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600">
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-200">No se encontraron proyectos</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Prueba con otra palabra clave.</p>
                            </div>`;
                        return;
                    }

                    data.forEach(project => {
                        const companyName = project.company?.name || 'Empresa Confidencial';
                        const date = new Date(project.created_at).toLocaleDateString();

                        const html = `
                            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-600 flex flex-col h-full hover:shadow-md transition-shadow duration-300">
                                <div class="flex-grow">
                                    <h4 class="text-xl font-bold text-indigo-600 dark:text-indigo-400 mb-4">${project.title}</h4>
                                    <p class="text-sm text-gray-500 mb-4">${companyName}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 line-clamp-3">${project.description}</p>
                                </div>
                                <div class="mt-4 flex justify-between items-center">
                                    <span class="text-xs text-gray-500">Publicado: ${date}</span>
                                    <a href="/projects/${project.id}" class="inline-flex items-center px-4 py-2 bg-indigo-600 rounded-md font-semibold text-xs text-white uppercase hover:bg-indigo-700">Ver más</a>
                                </div>
                            </div>`;
                        projectsContainer.insertAdjacentHTML('beforeend', html);
                    });
                })
                .catch(error => console.error('Error AJAX:', error));
            }

            /**
             * Actualiza el estilo visual del botón activo
             */
            function updateActiveButton(activeValue) {
                filterButtons.forEach(btn => {
                    if (btn.getAttribute('data-value') === activeValue) {
                        btn.classList.add('bg-indigo-600', 'text-white');
                        btn.classList.remove('text-indigo-600', 'dark:text-indigo-400');
                    } else {
                        btn.classList.remove('bg-indigo-600', 'text-white');
                        btn.classList.add('text-indigo-600', 'dark:text-indigo-400');
                    }
                });
            }

            //Escritura en el input
            searchInput.addEventListener('keyup', () => {
                performSearch(searchInput.value);
                updateActiveButton(searchInput.value);
            });

            // Clic en botones de filtro
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');

                    // Lógica de Limpieza: Si el filtro ya estaba activo, vaciamos
                    if (searchInput.value === value) {
                        searchInput.value = '';
                    } else {
                        searchInput.value = value;
                    }

                    performSearch(searchInput.value);
                    updateActiveButton(searchInput.value);
                });
            });
        });
    </script>
</x-app-layout>
