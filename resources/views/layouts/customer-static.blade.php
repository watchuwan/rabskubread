<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', $settings['app_name'] ?? 'Toko Roti') - {{ $settings['app_name'] ?? 'Toko Roti' }}</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('meta_description', $settings['seo_description'] ?? '')">
    <meta name="keywords" content="@yield('meta_keywords', $settings['seo_keywords'] ?? '')">

    <!-- Open Graph -->
    <meta property="og:title" content="@yield('og_title', $settings['app_name'] ?? 'Toko Roti')">
    <meta property="og:description" content="@yield('og_description', $settings['seo_description'] ?? '')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', $settings['app_favicon'] ?? asset('favicon.ico'))">

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
<body class="min-h-screen bg-cream-50">
    <!-- Sidebar (Mobile) -->
    @include('layouts.partials.sidebar')

    <!-- Header -->
    @include('layouts.partials.header')

    <!-- Main Content -->
    <main class="pt-16">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('layouts.partials.footer')

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
