<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>503 - Maintenance | Toko Roti</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-cream-50 flex items-center justify-center p-4">
    <div class="max-w-2xl w-full text-center">
        <!-- Maintenance Illustration -->
        <div class="mb-8">
            <span class="text-9xl">🔧</span>
        </div>

        <!-- Error Message -->
        <h1 class="text-6xl font-bold text-neutral-900 mb-4">503</h1>
        <h2 class="text-2xl font-semibold text-neutral-700 mb-4">Sedang Dalam Perbaikan</h2>
        <p class="text-neutral-600 mb-8">
            Maaf, website kami sedang dalam perbaikan untuk memberikan pengalaman yang lebih baik. Kami akan segera kembali.
        </p>

        <!-- Countdown/Status -->
        <div class="card p-6 mb-8">
            <p class="text-sm text-neutral-600 mb-4">Estimasi waktu selesai:</p>
            <div class="grid grid-cols-4 gap-4">
                <div>
                    <p class="text-3xl font-bold text-cream-600">02</p>
                    <p class="text-xs text-neutral-500">Jam</p>
                </div>
                <div>
                    <p class="text-3xl font-bold text-cream-600">30</p>
                    <p class="text-xs text-neutral-500">Menit</p>
                </div>
            </div>
        </div>

        <!-- Notify Me -->
        <div class="mb-8">
            <p class="text-sm text-neutral-600 mb-4">Ingin tahu saat kami online kembali?</p>
            <form class="flex gap-2 max-w-md mx-auto">
                <input type="email" placeholder="Email Anda" class="input flex-1" />
                <button type="submit" class="btn-primary">Notify Me</button>
            </form>
        </div>

        <!-- Contact -->
        <div class="pt-8 border-t border-neutral-200">
            <p class="text-sm text-neutral-600 mb-2">Butuh bantuan segera?</p>
            <a href="mailto:support@tokoroti.com" class="text-cream-600 hover:text-cream-700 font-medium">
                support@tokoroti.com
            </a>
        </div>
    </div>
</body>
</html>
