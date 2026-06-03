<x-app-layout>
    <x-slot name="header">
        {{ __('Directorio de Proyectos') }}
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div class="max-w-2xl">
                    <h3 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight">Proyectos Disponibles</h3>
                    <p class="text-lg text-gray-600 leading-relaxed">Descubre oportunidades y postúlate a los proyectos de nuestras empresas colaboradoras.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 mb-8">
                <div class="mb-6">
                    <p class="text-sm font-bold text-gray-900 mb-3 uppercase tracking-wider">Filtros rápidos por tecnología:</p>
                    <div id="filterButtonsContainer" class="flex flex-wrap gap-2">
                        @foreach($skills as $skill)
                            <button
                                type="button"
                                class="filter-btn px-4 py-2 rounded-lg border border-gray-200 bg-white text-gray-700 hover:border-blue-600 hover:text-blue-700 transition-all duration-200 text-xs font-bold uppercase tracking-wider shadow-sm"
                                data-value="{{ $skill->name }}"
                            >
                                {{ $skill->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label for="searchInput" class="block text-sm font-bold text-gray-900 mb-2 uppercase tracking-wider">Buscador Avanzado</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input
                            type="text"
                            id="searchInput"
                            placeholder="Busca proyectos por título o descripción..."
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-600 focus:border-transparent sm:text-sm transition-all"
                        >
                    </div>
                </div>
            </div>

            <div id="projectsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($projects as $project)
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow flex flex-col h-full">
                        <div class="flex-grow">
                            <h4 class="text-xl font-bold text-gray-900 line-clamp-2 leading-tight mb-3">
                                {{ $project->title }}
                            </h4>
                            <div class="mb-3">
                                <span class="inline-flex items-center text-sm font-semibold text-emerald-600">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    {{ $project->company->name ?? 'Empresa Confidencial' }}
                                </span>
                            </div>
                            <p class="text-gray-600 text-sm line-clamp-3 mb-4">
                                {{ Str::limit($project->description, 100) }}
                            </p>
                        </div>
                        <div class="mt-auto pt-5 border-t border-gray-100 flex justify-between items-center">
                            <span class="text-xs text-gray-500 font-medium">
                                Publicado: {{ $project->created_at->diffForHumans() }}
                            </span>
                            <a href="{{ route('projects.show', $project) }}" class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-semibold rounded-lg text-white bg-blue-700 hover:bg-blue-800 transition-all">
                                Ver más
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-2xl p-10 text-center border border-gray-100 shadow-sm flex flex-col items-center justify-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No hay proyectos activos</h3>
                        <p class="text-gray-500 max-w-md mx-auto">Actualmente no hay ninguna oferta publicada. ¡Vuelve más tarde!</p>
                    </div>
                @endforelse
            </div>

            <div id="paginationContainer" class="mt-8">
                {{ $projects->links() }}
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
                        'Accept': 'application/json'          // Esperamos JSON como respuesta
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
                            <div class="col-span-full bg-white rounded-2xl p-10 text-center border border-gray-100 shadow-sm flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">No se encontraron proyectos</h3>
                                <p class="text-gray-500 max-w-md mx-auto">Prueba con otra palabra clave o usa un filtro diferente.</p>
                            </div>`;
                        return;
                    }

                    data.forEach(project => {
                        const companyName = project.company?.name || 'Empresa Confidencial';
                        const date = new Date(project.created_at).toLocaleDateString();
                        // Truncamos la descripción a 100 caracteres aprox si es muy larga
                        const description = project.description.length > 100 ? project.description.substring(0, 100) + '...' : project.description;

                        const html = `
                            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow flex flex-col h-full">
                                <div class="flex-grow">
                                    <h4 class="text-xl font-bold text-gray-900 line-clamp-2 leading-tight mb-3">
                                        ${project.title}
                                    </h4>
                                    <div class="mb-3">
                                        <span class="inline-flex items-center text-sm font-semibold text-emerald-600">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            ${companyName}
                                        </span>
                                    </div>
                                    <p class="text-gray-600 text-sm line-clamp-3 mb-4">${description}</p>
                                </div>
                                <div class="mt-auto pt-5 border-t border-gray-100 flex justify-between items-center">
                                    <span class="text-xs text-gray-500 font-medium">Publicado: ${date}</span>
                                    <a href="/projects/${project.id}" class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-semibold rounded-lg text-white bg-blue-700 hover:bg-blue-800 transition-all">Ver más</a>
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
                        btn.classList.add('bg-blue-700', 'text-white', 'border-blue-700');
                        btn.classList.remove('bg-white', 'text-gray-700', 'border-gray-200', 'hover:border-blue-600', 'hover:text-blue-700');
                    } else {
                        btn.classList.remove('bg-blue-700', 'text-white', 'border-blue-700');
                        btn.classList.add('bg-white', 'text-gray-700', 'border-gray-200', 'hover:border-blue-600', 'hover:text-blue-700');
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
