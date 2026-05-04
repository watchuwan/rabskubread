<div class="min-h-screen flex items-center justify-center bg-cream-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-lg border border-neutral-200 p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-neutral-900 mb-2">Reset Password</h2>
                <p class="text-neutral-600">Masukkan password baru Anda</p>
            </div>

            <!-- Form -->
            <form wire:submit="resetPassword" class="space-y-6">
                <!-- Email (readonly) -->
                <div>
                    <label for="email" class="block text-sm font-medium text-neutral-700 mb-2">
                        Email
                    </label>
                    <input
                        type="email"
                        id="email"
                        wire:model="email"
                        class="w-full px-4 py-3 border border-neutral-300 rounded-lg bg-neutral-50"
                        readonly
                    />
                    @error('email')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-neutral-700 mb-2">
                        Password Baru
                    </label>
                    <input
                        type="password"
                        id="password"
                        wire:model="password"
                        class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cream-500 focus:border-transparent"
                        placeholder="Minimal 8 karakter"
                        required
                    />
                    @error('password')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-neutral-700 mb-2">
                        Konfirmasi Password
                    </label>
                    <input
                        type="password"
                        id="password_confirmation"
                        wire:model="password_confirmation"
                        class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cream-500 focus:border-transparent"
                        placeholder="Ulangi password baru"
                        required
                    />
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full btn-primary py-3"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove>Reset Password</span>
                    <span wire:loading>Memproses...</span>
                </button>

                <!-- Back to Login -->
                <div class="text-center">
                    <a
                        href="{{ route('login') }}"
                        class="text-sm text-cream-600 hover:text-cream-700 font-medium"
                        wire:navigate
                    >
                        ← Kembali ke Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
