{{-- Modern Sticky Header --}}
<header
    x-data="{ scrolled: false, userMenu: false }"
    x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 10)"
    @click.outside="userMenu = false"
    :class="scrolled ? 'shadow-md bg-white/95 backdrop-blur-md' : 'bg-white shadow-sm'"
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
>
    {{-- Top Bar --}}
    <div class="bg-amber-600 text-white text-xs py-1.5 text-center hidden sm:block">
        <span>🚚 Gratis ongkir untuk pembelian di atas Rp {{ number_format($settings['free_shipping_min'] ?? 200000, 0, ',', '.') }} &nbsp;|&nbsp; 🕐 {{ $settings['business_hours'] ?? 'Buka setiap hari 06.00 - 21.00' }}</span>
    </div>

    {{-- Main Navbar --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 gap-4">

            {{-- Logo --}}
            <div class="flex items-center gap-3 flex-shrink-0">
                {{-- Mobile Menu Button --}}
                <button
                    x-data
                    @click="$dispatch('sidebar-toggle')"
                    class="lg:hidden p-2 rounded-lg hover:bg-amber-50 text-neutral-600 hover:text-amber-600 transition-colors"
                    aria-label="Toggle menu"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group" wire:navigate>
                    <div class="w-9 h-9 bg-gradient-to-br from-amber-400 to-amber-600 rounded-xl flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow overflow-hidden">
                        @if($settings['app_logo'] ?? null)
                            <img src="{{ $settings['app_logo'] }}" alt="{{ $settings['app_name'] ?? 'Logo' }}" class="w-full h-full object-cover" />
                        @else
                            <span class="text-xl">🥐</span>
                        @endif
                    </div>
                    <div class="hidden sm:block">
                        <span class="font-display text-lg font-bold text-neutral-900 leading-none block">{{ $settings['app_name'] ?? 'Toko Roti' }}</span>
                        <span class="text-[10px] text-amber-600 font-medium tracking-wide uppercase">{{ $settings['app_tagline'] ?? 'Artisan Bakery' }}</span>
                    </div>
                </a>
            </div>

            {{-- Navigation Menu (Desktop) --}}
            <nav class="hidden lg:flex flex-1 items-center justify-center gap-8">
                <a href="{{ route('home') }}" wire:navigate class="text-sm font-medium text-neutral-700 hover:text-amber-600 transition-colors {{ request()->routeIs('home') ? 'text-amber-600' : '' }}">
                    Beranda
                </a>
                <a href="{{ route('products.index') }}" wire:navigate class="text-sm font-medium text-neutral-700 hover:text-amber-600 transition-colors {{ request()->routeIs('products.*') ? 'text-amber-600' : '' }}">
                    Produk
                </a>
                <a href="{{ route('about') }}" wire:navigate class="text-sm font-medium text-neutral-700 hover:text-amber-600 transition-colors {{ request()->routeIs('about') ? 'text-amber-600' : '' }}">
                    Tentang
                </a>
                <a href="{{ route('contact') }}" wire:navigate class="text-sm font-medium text-neutral-700 hover:text-amber-600 transition-colors {{ request()->routeIs('contact') ? 'text-amber-600' : '' }}">
                    Kontak
                </a>
            </nav>

            {{-- Right Actions --}}
            <div class="flex items-center gap-1 sm:gap-2">

                {{-- Wishlist (Auth) --}}
                @auth('customer')
                    <livewire:customer.wishlist.wishlist-counter />
                @endauth

                {{-- Cart (Auth) --}}
                @auth('customer')
                    <livewire:customer.cart.cart-mini-display />
                @endauth

                {{-- User Menu / Auth Buttons --}}
                @auth('customer')
                    <div class="relative">
                        <button @click="userMenu = !userMenu" class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-xl hover:bg-amber-50 transition-colors">
                            <div class="w-7 h-7 bg-gradient-to-br from-amber-400 to-amber-600 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-xs font-bold text-white">
                                    {{ strtoupper(substr(auth()->guard('customer')->user()->name, 0, 1)) }}
                                </span>
                            </div>
                            <span class="hidden sm:block text-sm font-medium text-neutral-700 max-w-[100px] truncate">
                                {{ auth()->guard('customer')->user()->name }}
                            </span>
                            <svg class="w-3.5 h-3.5 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="userMenu" x-transition class="absolute right-0 mt-2 w-52 bg-white rounded-lg shadow-lg border border-neutral-200 overflow-hidden z-50" style="display: none;">
                            <div class="px-3 py-2 border-b border-neutral-100">
                                <p class="text-xs text-neutral-500">Masuk sebagai</p>
                                <p class="text-sm font-semibold text-neutral-900 truncate">{{ auth()->guard('customer')->user()->name }}</p>
                            </div>
                            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 hover:bg-amber-50">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                Dashboard
                            </a>
                            <a href="{{ route('profile.index') }}" wire:navigate class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 hover:bg-amber-50">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                Profil Saya
                            </a>
                            <a href="{{ route('orders.index') }}" wire:navigate class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 hover:bg-amber-50">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                Pesanan Saya
                            </a>
                            <a href="{{ route('wishlist.index') }}" wire:navigate class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 hover:bg-amber-50">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                Wishlist
                            </a>
                            <a href="{{ route('addresses.index') }}" wire:navigate class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 hover:bg-amber-50">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                Alamat
                            </a>
                            <div class="border-t border-neutral-100"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" wire:navigate class="btn-ghost text-sm px-3 py-2">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" wire:navigate class="btn-primary text-sm px-4 py-2">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

</header>
