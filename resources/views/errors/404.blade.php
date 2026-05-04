<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Halaman Tidak Ditemukan | Toko Roti</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-cream-50 flex items-center justify-center p-4">
    <div class="max-w-2xl w-full text-center">
        <!-- 404 Illustration -->
        <div class="mb-8">
            <span class="text-9xl">🥐</span>
        </div>

        <!-- Error Message -->
        <h1 class="text-6xl font-bold text-neutral-900 mb-4">404</h1>
        <h2 class="text-2xl font-semibold text-neutral-700 mb-4">Oops! Halaman Tidak Ditemukan</h2>
        <p class="text-neutral-600 mb-8">
            Maaf, halaman yang Anda cari tidak dapat ditemukan. Mungkin halaman telah dipindahkan atau tidak ada.
        </p>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('home') }}" class="btn-primary inline-flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Kembali ke Beranda
            </a>
            <a href="{{ route('products.index') }}" class="btn-secondary inline-flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                Belanja Sekarang
            </a>
        </div>

        <!-- Help Link -->
        <div class="mt-8 pt-8 border-t border-neutral-200">
            <p class="text-sm text-neutral-600 mb-2">Masih butuh bantuan?</p>
            <a href="{{ route('contact') }}" class="text-cream-600 hover:text-cream-700 font-medium">
                Hubungi Customer Service →
            </a>
        </div>
    </div>
</body>
</html>
