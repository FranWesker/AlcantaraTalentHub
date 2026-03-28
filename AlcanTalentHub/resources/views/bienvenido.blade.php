<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido | Alcantara Talent Hub</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 text-gray-900">
    <nav class="w-full bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-end items-center h-16 space-x-4">

                <a href="{{ route('login') }}"
                   class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-600 transition-colors duration-200 rounded-md hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Iniciar sesión
                </a>

                <a href="{{ route('register') }}"
                   class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white transition-colors duration-200 bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Registrarse
                </a>

            </div>
        </div>
    </nav>
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    </main>
</body>
</html>
