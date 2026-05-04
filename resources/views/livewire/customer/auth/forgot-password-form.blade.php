<div class="min-h-screen flex items-center justify-center bg-cream-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-lg border border-neutral-200 p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-neutral-900 mb-2">Lupa Password?</h2>
                <p class="text-neutral-600">Masukkan email Anda dan kami akan mengirimkan link untuk reset password</p>
            </div>

            <!-- Form -->
            <form wire:submit="sendResetLink" class="space-y-6">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-neutral-700 mb-2">
                        Email
                    </label>
                    <input
                        type="email"
                        id="email"
                        wire:model="email"
                        class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cream-500 focus:border-transparent"
                        placeholder="nama@email.com"
                        required
                    />
                    @error('email')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full btn-primary py-3"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove>Kirim Link Reset Password</span>
                    <span wire:loading>Mengirim...</span>
                </button>

                <!-- Development: Show Reset Link -->
                @if($resetLink)
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm font-medium text-blue-900 mb-2">
                            🔧 Development Mode - Reset Link:
                        </p>
                        <div class="flex gap-2">
                            <input
                                type="text"
                                value="{{ $resetLink }}"
                                readonly
                                class="flex-1 text-xs px-3 py-2 bg-white border border-blue-300 rounded"
                            />
                            <button
                                type="button"
                                onclick="navigator.clipboard.writeText('{{ $resetLink }}')"
                                class="px-3 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700"
                            >
                                Copy
                            </button>
                        </div>
                        <a
                            href="{{ $resetLink }}"
                            class="text-xs text-blue-600 hover:text-blue-700 mt-2 inline-block"
                            wire:navigate
                        >
                            → Klik untuk reset password
                        </a>
                    </div>
                @endif

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
