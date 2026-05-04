@props(['open' => false])

<div 
    x-data="{ open: @js($open) }"
    @sidebar-toggle.window="open = !open"
    x-show="open"
    x-cloak
    class="relative z-50 lg:hidden"
    role="dialog"
    aria-modal="true"
>
    <!-- Backdrop -->
    <div 
        x-show="open"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-neutral-900/80"
        aria-hidden="true"
    ></div>
    
    <!-- Sidebar Panel -->
    <div 
        x-show="open"
        x-transition:enter="transition ease-in-out duration-300 transform"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in-out duration-300 transform"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-0 flex"
    >
        <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white shadow-xl">
            <!-- Close Button -->
            <button
                @click="open = false"
                class="absolute top-4 right-4 p-2 rounded-lg hover:bg-neutral-100 transition-colors"
                aria-label="Close menu"
            >
                <svg class="w-6 h-6 text-neutral-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            
            <!-- Logo -->
            <div class="px-6 py-6 border-b border-neutral-200">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-cream-500 rounded-lg flex items-center justify-center overflow-hidden">
                        @if($settings['app_logo'] ?? null)
                            <img src="{{ $settings['app_logo'] }}" alt="{{ $settings['app_name'] ?? 'Logo' }}" class="w-full h-full object-cover" />
                        @else
                            <span class="text-2xl">🥐</span>
                        @endif
                    </div>
                    <span class="text-xl font-bold text-neutral-900">{{ $settings['app_name'] ?? 'Toko Roti' }}</span>
                </a>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a
                    href="{{ route('home') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-neutral-100 transition-colors {{ request()->routeIs('home') ? 'bg-cream-100 text-cream-800' : 'text-neutral-700' }}"
                    wire:navigate
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="font-medium">Beranda</span>
                </a>

                <a
                    href="{{ route('products.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-neutral-100 transition-colors {{ request()->routeIs('products.*') ? 'bg-cream-100 text-cream-800' : 'text-neutral-700' }}"
                    wire:navigate
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span class="font-medium">Produk</span>
                </a>

                @auth('customer')
                    <div class="pt-4 mt-4 border-t border-neutral-200">
                        <p class="px-4 text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-2">
                            Akun Saya
                        </p>

                        <a
                            href="{{ route('dashboard') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-neutral-100 transition-colors {{ request()->routeIs('dashboard') ? 'bg-cream-100 text-cream-800' : '' }}"
                            wire:navigate
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span class="font-medium">Dashboard</span>
                        </a>

                        <a
                            href="{{ route('orders.index') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-neutral-100 transition-colors"
                            wire:navigate
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="font-medium">Pesanan</span>
                        </a>

                        <a
                            href="{{ route('wishlist.index') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-neutral-100 transition-colors"
                            wire:navigate
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <span class="font-medium">Wishlist</span>
                        </a>

                        <a
                            href="{{ route('addresses.index') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-neutral-100 transition-colors"
                            wire:navigate
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="font-medium">Alamat</span>
                        </a>

                        <a
                            href="{{ route('cart.index') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-neutral-100 transition-colors"
                            wire:navigate
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <span class="font-medium">Keranjang</span>
                        </a>
                    </div>

                    <div class="pt-4 mt-4 border-t border-neutral-200">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button
                                type="submit"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-neutral-100 transition-colors text-neutral-700"
                            >
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span class="font-medium">Logout</span>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="pt-4 mt-4 border-t border-neutral-200">
                        <a 
                            href="{{ route('login') }}" 
                            class="block w-full btn-primary text-center mb-2"
                            wire:navigate
                        >
                            Masuk
                        </a>
                        <a 
                            href="{{ route('register') }}" 
                            class="block w-full btn-secondary text-center"
                            wire:navigate
                        >
                            Daftar
                        </a>
                    </div>
                @endauth
            </nav>
        </div>
    </div>
</div>
