<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
                <div class="w-16 h-16 bg-cream-500 rounded-xl flex items-center justify-center">
                    <span class="text-4xl">🥐</span>
                </div>
            </a>
            <h2 class="mt-6 text-3xl font-bold text-neutral-900">
                {{ __('Buat Akun Baru') }}
            </h2>
            <p class="mt-2 text-sm text-neutral-600">
                {{ __('Sudah punya akun?') }}
                <a href="{{ route('login') }}" class="font-medium text-cream-600 hover:text-cream-700" wire:navigate>
                    {{ __('masuk di sini') }}
                </a>
            </p>
        </div>

        <!-- Register Form -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <form wire:submit="register" class="space-y-5">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-neutral-700 mb-2">
                        {{ __('Nama Lengkap') }}
                    </label>
                    <input
                        id="name"
                        wire:model="name"
                        type="text"
                        placeholder="John Doe"
                        autocomplete="name"
                        class="w-full border border-neutral-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cream-500 focus:border-cream-500 @error('name') border-danger focus:ring-danger @enderror"
                    />
                    @error('name')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-neutral-700 mb-2">
                        {{ __('Email') }}
                    </label>
                    <input
                        id="email"
                        wire:model="email"
                        type="email"
                        placeholder="nama@email.com"
                        autocomplete="email"
                        class="w-full border border-neutral-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cream-500 focus:border-cream-500 @error('email') border-danger focus:ring-danger @enderror"
                    />
                    @error('email')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-neutral-700 mb-2">
                        {{ __('Password') }}
                    </label>
                    <input
                        id="password"
                        wire:model="password"
                        type="password"
                        placeholder="••••••••"
                        autocomplete="new-password"
                        class="w-full border border-neutral-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cream-500 focus:border-cream-500 @error('password') border-danger focus:ring-danger @enderror"
                    />
                    @error('password')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-neutral-700 mb-2">
                        {{ __('Konfirmasi Password') }}
                    </label>
                    <input
                        id="password_confirmation"
                        wire:model="password_confirmation"
                        type="password"
                        placeholder="••••••••"
                        autocomplete="new-password"
                        class="w-full border border-neutral-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cream-500 focus:border-cream-500"
                    />
                </div>

                <!-- Terms and Conditions -->
                <div class="flex items-start">
                    <input
                        id="terms"
                        wire:model="terms"
                        type="checkbox"
                        class="w-4 h-4 text-cream-600 border-neutral-300 rounded focus:ring-cream-500 mt-0.5"
                    />
                    <label for="terms" class="ml-2 block text-sm text-neutral-700">
                        {{ __('Saya setuju dengan') }}
                        <a href="#" class="text-cream-600 hover:text-cream-700 underline">{{ __('Syarat & Ketentuan') }}</a>
                        {{ __('dan') }}
                        <a href="#" class="text-cream-600 hover:text-cream-700 underline">{{ __('Kebijakan Privasi') }}</a>
                    </label>
                    @error('terms')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="btn-primary w-full py-3"
                >
                    <span wire:loading.remove>{{ __('Buat Akun') }}</span>
                    <span wire:loading class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __('Memproses...') }}
                    </span>
                </button>
            </form>

            <!-- Divider -->
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-neutral-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-neutral-500">{{ __('Atau daftar dengan') }}</span>
                    </div>
                </div>

                <!-- Social Register -->
                <div class="mt-6 grid grid-cols-2 gap-3">
                    <a 
                        href="{{ route('auth.redirect', ['provider' => 'google']) }}"
                        class="flex items-center justify-center gap-2 px-4 py-2 border border-neutral-300 rounded-lg hover:bg-neutral-50 transition-colors"
                    >
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        <span class="text-sm font-medium text-neutral-700">Google</span>
                    </a>
                    <a 
                        href="{{ route('auth.redirect', ['provider' => 'facebook']) }}"
                        class="flex items-center justify-center gap-2 px-4 py-2 border border-neutral-300 rounded-lg hover:bg-neutral-50 transition-colors"
                    >
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        <span class="text-sm font-medium text-neutral-700">Facebook</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="mt-8 text-center">
            <a href="{{ route('home') }}" class="text-sm text-neutral-600 hover:text-neutral-900" wire:navigate>
                ← {{ __('Kembali ke beranda') }}
            </a>
        </div>
    </div>
</div>
