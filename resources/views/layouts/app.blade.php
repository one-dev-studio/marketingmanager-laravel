<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' - ' : '' }}MarketPulse</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important}</style>
    @stack('styles')
</head>
<body class="bg-gray-50 antialiased">
    <div 
        id="app" 
        class="min-h-screen flex"
        x-data="{ sidebarCollapsed: false }"
    >
        @php
            $organizationId = request()->route('organizationId');
            $pageTitle = $title ?? 'Dashboard';
        @endphp

        @if($organizationId)
            @include('partials.layout.app-sidebar')

            <x-partials.layout.sidebar-inset>
                <x-partials.layout.header 
                    :title="$pageTitle"
                    :showMobileMenuToggle="true"
                    :showOrganizationSwitcher="true"
                    :showCalendarDialog="true"
                    :showReviewIndicator="true"
                    :showNotifications="true"
                />

                <x-partials.layout.main>
                    @yield('content')
                </x-partials.layout.main>
            </x-partials.layout.sidebar-inset>
        @else
            <div class="flex-1 min-w-0 relative z-10">
                <x-partials.layout.header 
                    :title="$pageTitle"
                    :showMobileMenuToggle="false"
                    :showOrganizationSwitcher="false"
                    :showCalendarDialog="false"
                    :showReviewIndicator="false"
                    :showNotifications="false"
                />
                <x-partials.layout.main>
                    @yield('content')
                </x-partials.layout.main>
            </div>
        @endif
    </div>

    <div id="command-popover-mount"></div>

    @stack('scripts')
</body>
</html>
