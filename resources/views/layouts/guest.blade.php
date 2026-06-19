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

<body class="font-sans text-slate-950 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-50">
        <div
            class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-lg shadow-slate-900/10 rounded-xl border border-slate-200/60 relative animate-fade-in">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-slate-500 to-slate-700 rounded-t-xl">
            </div>

            <div class="mb-6 flex justify-center">
                <x-application-logo />
            </div>

            {{ $slot }}
        </div>
    </div>
</body>

</html>
