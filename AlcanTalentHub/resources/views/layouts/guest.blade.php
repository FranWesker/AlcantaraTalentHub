<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Alcántara Talent Hub') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased h-full">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50">
        <div class="mb-8 text-center">
            <a href="/" class="focus:outline-none focus:ring-2 focus:ring-blue-600 rounded p-2 inline-block">
                <span class="text-3xl font-extrabold text-blue-900">Alcántara<span class="text-blue-600">Talent</span></span>
                <p class="text-xs text-gray-500 uppercase tracking-widest mt-1">Networking Académico</p>
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white shadow-xl shadow-gray-200/50 sm:rounded-2xl border border-gray-100">
            {{ $slot }}
        </div>

        <p class="mt-8 text-center text-sm text-gray-400">
            Alcántara Talent Hub &copy; {{ date('Y') }}
        </p>
    </div>
</body>
</html>
