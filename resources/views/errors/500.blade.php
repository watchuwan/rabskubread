<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 - Server Error | Toko Roti</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-cream-50 flex items-center justify-center p-4">
    <div class="max-w-2xl w-full text-center">
        <!-- Error Illustration -->
        <div class="mb-8">
            <span class="text-9xl">😵</span>
        </div>

        <!-- Error Message -->
        <h1 class="text-6xl font-bold text-neutral-900 mb-4">500</h1>
        <h2 class="text-2xl font-semibold text-neutral-700 mb-4">Oops! Terjadi Kesalahan Server</h2>
        <p class="text-neutral-600 mb-8">
            Maaf, terjadi kesalahan pada server kami. Tim kami sedang bekerja untuk memperbaikinya. Silakan coba lagi nanti.
        </p>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('home') }}" class="btn-primary inline-flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Kembali ke Beranda
            </a>
            <a href="javascript:window.location.reload()" class="btn-secondary inline-flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Coba Lagi
            </a>
        </div>

        <!-- Help Link -->
        <div class="mt-8 pt-8 border-t border-neutral-200">
            <p class="text-sm text-neutral-600 mb-2">Masih mengalami masalah?</p>
            <a href="{{ route('contact') }}" class="text-cream-600 hover:text-cream-700 font-medium">
                Hubungi Customer Service →
            </a>
        </div>
    </div>
</body>
</html>
