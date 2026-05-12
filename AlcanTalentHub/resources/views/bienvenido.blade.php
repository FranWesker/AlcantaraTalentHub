<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alcántara Talent Hub - Conectando Talento y Empresa</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 selection:bg-blue-600 selection:text-white flex flex-col min-h-screen">

    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ url('/') }}" aria-label="Inicio de Alcántara Talent Hub" class="focus:outline-none focus:ring-2 focus:ring-blue-600 rounded">
                        <span class="text-2xl font-bold text-blue-800 tracking-tight">Alcántara<span class="text-blue-600">Talent</span></span>
                    </a>
                </div>

                <nav aria-label="Navegación de usuario" class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 rounded px-3 py-2 transition-colors">
                                Panel de Control
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 rounded px-3 py-2 transition-colors">
                                Iniciar Sesión
                            </a>
                        @endauth
                    @endif
                </nav>
            </div>
        </div>
    </header>

    <main id="main-content" class="flex-grow">

        <section aria-labelledby="hero-heading" class="relative bg-white overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
                <div class="text-center max-w-4xl mx-auto">
                    <h1 id="hero-heading" class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
                        Impulsa tu futuro profesional con <span class="text-blue-700">Alcántara Talent Hub</span>
                    </h1>
                    <p class="text-lg sm:text-xl text-gray-600 mb-10 max-w-2xl mx-auto">
                        La plataforma definitiva de networking donde el talento académico emergente se encuentra con las mejores empresas del mercado laboral local.
                    </p>

                    <div class="flex flex-col sm:flex-row justify-center items-center gap-4 sm:gap-6">
                        <a href="{{ route('register', ['role' => 'student']) }}"
                           class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-3.5 border border-transparent text-base font-semibold rounded-lg text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 shadow-sm transition-all"
                           aria-label="Registrarse como estudiante">
                            Soy Estudiante
                            <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                        </a>

                        <a href="{{ route('register', ['role' => 'company']) }}"
                           class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-3.5 border-2 border-gray-900 text-base font-semibold rounded-lg text-gray-900 bg-transparent hover:bg-gray-900 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-all"
                           aria-label="Registrarse como empresa">
                            Soy Empresa
                            <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </a>
                    </div>
                </div>
            </div>

            <div aria-hidden="true" class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-gray-50 to-transparent pointer-events-none"></div>
        </section>

        <section aria-labelledby="features-heading" class="py-16 sm:py-24 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 id="features-heading" class="sr-only">Beneficios de la plataforma</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-16">
                    <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Para Estudiantes</h3>
                        <p class="text-gray-600">
                            Muestra tus proyectos, gestiona tu currículum y aplica a ofertas diseñadas específicamente para perfiles junior y recién graduados. Da el primer paso seguro hacia tu carrera.
                        </p>
                    </div>

                    <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Para Empresas</h3>
                        <p class="text-gray-600">
                            Accede a una bolsa de talento local, publica tus proyectos o vacantes y descubre perfiles validados y listos para aportar valor a tu equipo desde el primer día.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="md:flex md:items-center md:justify-between text-center md:text-left">
                <div class="flex justify-center md:justify-start mb-4 md:mb-0">
                    <span class="text-xl font-bold text-gray-900 tracking-tight">Alcántara<span class="text-blue-600">Talent</span></span>
                </div>
                <p class="text-sm text-gray-500">
                    &copy; {{ date('Y') }} Alcántara Talent Hub. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </footer>

</body>
</html>
