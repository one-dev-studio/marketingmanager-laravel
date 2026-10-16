<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'MarketPulse') }}</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">

    @routes
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/inertia/app.tsx'])
    @inertiaHead
</head>
<body class="bg-gray-50 font-sans antialiased">
    @inertia
</body>
</html>
