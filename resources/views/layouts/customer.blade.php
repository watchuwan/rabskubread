<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? $settings['app_name'] ?? 'Toko Roti' }} - {{ $settings['app_name'] ?? 'Toko Roti' }}</title>

    <!-- SEO -->
    <meta name="description" content="{{ $description ?? $settings['seo_description'] ?? '' }}">
    <meta name="keywords" content="{{ $keywords ?? $settings['seo_keywords'] ?? '' }}">

    <!-- Open Graph -->
    <meta property="og:title" content="{{ $ogTitle ?? ($title ?? $settings['app_name'] ?? 'Toko Roti') }}">
    <meta property="og:description" content="{{ $ogDescription ?? ($description ?? $settings['seo_description'] ?? '') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $ogImage ?? ($settings['app_favicon'] ?? asset('favicon.ico')) }}">

    <!-- Theme: colors & fonts (admin-configurable) -->
    <x-theme-vars />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ $settings['app_favicon'] ?? asset('favicon.ico') }}">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
    </style>

    @stack('styles')
</head>
<body class="min-h-screen bg-cream-50 flex flex-col">

    <!-- Sidebar (Mobile) -->
    @include('layouts.partials.sidebar')

    <!-- Header -->
    @include('layouts.partials.header')

    <!-- Main Content (pt = top-bar 30px + navbar 64px = ~94px) -->
    <main class="flex-1 pt-16 sm:pt-[5.875rem]">
        {{ $slot }}
    </main>

    <!-- Footer -->
    @include('layouts.partials.footer')

    <!-- Toast Notification -->
    <x-toast />

    <!-- Confirm Modal -->
    <x-confirm-modal />

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
