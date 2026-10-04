<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'JobTracker Pro') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <a href="/" class="mb-6 flex items-center gap-2.5">
                <span class="h-7 w-7 rounded bg-gray-900 text-white flex items-center justify-center text-xs font-bold">JT</span>
                <span class="font-semibold text-sm tracking-tight">JobTracker <span class="text-[11px] font-medium text-gray-400 uppercase tracking-wide">Pro</span></span>
            </a>

            <div class="w-full sm:max-w-md px-6 py-5 bg-white border border-gray-200 sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
