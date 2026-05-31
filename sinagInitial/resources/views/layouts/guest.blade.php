<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center bg-gray-100">
            <!-- SINAG Logo/Title -->
            <div class="mb-8 text-center">
                <span class="text-4xl md:text-5xl font-bold tracking-wide" style="color:#6366F1; letter-spacing:2px;">SINAG</span>
            </div>
            <div class="w-full" style="max-width: 400px;">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    {{ $slot }}
                </div>
            </div>
            <div class="mt-8 text-xs text-gray-500 text-center max-w-xs">
                By signing in, you agree to the Safe Spaces Act (RA 11313) data privacy protocols and PSU's Code of Conduct.
            </div>
        </div>
    </body>
</html>
