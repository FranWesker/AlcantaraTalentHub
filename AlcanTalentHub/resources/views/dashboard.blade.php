<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Panel Principal') }} || {{ __('Añadir habilidades') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Comprobamos directamente el rol del usuario en la base de datos --}}
                    @if(auth()->user()->role === 'estudiante')
                        <h1 class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                            Bienvenido Estudiante: {{ auth()->user()->name }}
                        </h1>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">
                            Has iniciado sesión en tu cuenta de estudiante. Desde aquí podrás configurar tus habilidades y buscar proyectos.
                        </p>
                    @elseif(auth()->user()->role === 'empresa')
                        <h1 class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                            Bienvenida Empresa: {{ auth()->user()->name }}
                        </h1>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">
                            Has iniciado sesión como cuenta de empresa. Desde aquí podrás publicar proyectos y buscar talento.
                        </p>
                    @endif
                    </div>
            </div>
        </div>
    </div>
</x-app-layout>
