<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Toko Bu Sis') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-50 selection:bg-indigo-500 selection:text-white">
        
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            
            <div class="mb-8 mt-10 sm:mt-0">
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white font-bold text-3xl shadow-lg group-hover:scale-105 transition-transform duration-300">
                        S
                    </div>
                    <span class="font-black text-3xl tracking-tight text-gray-900 group-hover:text-indigo-600 transition-colors duration-300">Toko Bu Sis</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md px-8 py-10 bg-white shadow-xl border border-gray-100 sm:rounded-[2rem] overflow-hidden">
                {{ $slot }}
            </div>
            
            <div class="mt-8 mb-8 text-center text-xs font-medium text-gray-500">
                &copy; {{ date('Y') }} Toko Bu Sis Tulungagung.<br>All rights reserved.
            </div>

        </div>

    </body>
</html>