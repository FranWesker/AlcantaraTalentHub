<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Alcántara Talent Hub') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased h-full text-gray-900">
    <div class="min-h-screen flex flex-col">
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                            {{ $header }}
                        </h1>
                        </div>
                </div>
            </header>
        @endisset

        <main id="main-content" class="flex-grow">
            <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

                {{-- Mensaje de Éxito --}}
                @if (session('success'))
                    <div class="mb-8 bg-green-50 border-l-4 border-green-600 p-4 rounded-r-lg shadow-sm" role="alert">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Mensaje de Error CV --}}
                @if (session('error'))
                    <div class="mb-8 bg-red-50 border-l-4 border-red-600 p-4 rounded-r-lg shadow-sm" role="alert">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <strong class="text-sm font-bold text-red-800 block mb-1">¡Error!</strong>
                                <p class="text-sm text-red-700">
                                    {{ session('error') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Slot principal del contenido --}}
                {{ $slot }}

            </div>
        </main>

        <footer class="bg-white border-t border-gray-200 py-8 mt-auto">
            <div class="max-w-7xl mx-auto px-4 text-center md:text-left md:flex md:justify-between items-center">
                <p class="text-sm text-gray-500">
                    &copy; {{ date('Y') }} Alcántara Talent Hub. Conectando talento local.
                </p>
                <div class="mt-4 md:mt-0 flex justify-center gap-6">
                    <a href="#" class="text-xs text-gray-400 hover:text-blue-600 transition-colors">Privacidad</a>
                    <a href="#" class="text-xs text-gray-400 hover:text-blue-600 transition-colors">Términos</a>
                    <a href="#" class="text-xs text-gray-400 hover:text-blue-600 transition-colors">Soporte</a>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
