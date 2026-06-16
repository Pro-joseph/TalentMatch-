<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'TalentMatch') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-warm-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-warm-50">
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-sm rounded-lg border border-warm-200 relative animate-fade-in">
            <div class="absolute top-0 left-0 right-0 h-1 bg-brand-600 rounded-t-lg"></div>

            <div class="mb-6 flex justify-center">
                <x-application-logo />
            </div>

            {{ $slot }}
        </div>
    </div>
</body>
</html>
