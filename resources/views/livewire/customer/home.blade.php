<div>

    {{-- ===== HERO SECTION ===== --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-amber-50 via-cream-50 to-white">
        {{-- Background decorations --}}
        <div class="absolute top-0 right-0 w-96 h-96 bg-amber-100 rounded-full blur-3xl opacity-40 -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-amber-200 rounded-full blur-3xl opacity-30 translate-y-1/2 -translate-x-1/4"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

                {{-- Text Content --}}
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-amber-100 text-amber-700 text-sm font-semibold rounded-full mb-6 border border-amber-200">
                        <span class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></span>
                        {{ $settings['hero_badge_text'] ?? 'Fresh from the Oven Every Day' }}
                    </div>

                    <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-bold text-neutral-900 leading-tight mb-6">
                        {{ $settings['app_name'] ?? 'Toko Roti' }}
                        <span class="block text-amber-500">{{ $settings['app_tagline'] ?? 'Roti Artisan Segar & Lezat' }}</span>
                    </h1>

                    <p class="text-lg text-neutral-600 mb-8 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                        {{ $settings['app_description'] ?? 'Dibuat dengan tangan oleh baker berpengalaman menggunakan bahan-bahan premium pilihan.' }}
                    </p>

                    <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start mb-10">
                        <a href="{{ route('products.index') }}" wire:navigate class="btn-primary px-8 py-3.5 text-base">
                            Belanja Sekarang
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                        </a>
                        <a href="#products" class="btn-secondary px-8 py-3.5 text-base">
                            Lihat Kategori
                        </a>
                    </div>

                    {{-- Stats --}}
                    <div class="flex items-center gap-8 justify-center lg:justify-start">
                        <div class="text-center lg:text-left">
                            <p class="font-display text-2xl font-bold text-neutral-900">{{ $stats['products'] }}+</p>
                            <p class="text-xs text-neutral-500 mt-0.5">Produk Tersedia</p>
                        </div>
                        <div class="w-px h-10 bg-neutral-200"></div>
                        <div class="text-center lg:text-left">
                            <p class="font-display text-2xl font-bold text-neutral-900">{{ number_format($stats['customers']) }}+</p>
                            <p class="text-xs text-neutral-500 mt-0.5">Pelanggan Puas</p>
                        </div>
                        <div class="w-px h-10 bg-neutral-200"></div>
                        <div class="text-center lg:text-left">
                            <p class="font-display text-2xl font-bold text-neutral-900">{{ number_format($stats['rating'], 1) }}★</p>
                            <p class="text-xs text-neutral-500 mt-0.5">Rating Rata-rata</p>
                        </div>
                    </div>
                </div>

                {{-- Hero Visual --}}
                <div class="relative flex justify-center lg:justify-end">
                    <div class="relative w-full max-w-md">

                        @if($settings['hero_image'] ?? null)
                            {{-- Custom Hero Image --}}
                            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-amber-100">
                                <img src="{{ $settings['hero_image'] }}" alt="Hero" class="w-full h-auto object-cover" />
                            </div>
                        @else
                            {{-- Default Card --}}
                            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-amber-100">
                            {{-- Header gradient --}}
                            <div class="bg-gradient-to-br from-amber-400 to-amber-600 px-6 pt-6 pb-10 relative overflow-hidden">
                                <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full"></div>
                                <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-white/10 rounded-full"></div>
                                <p class="text-white/80 text-xs font-semibold uppercase tracking-widest mb-1">Menu Favorit</p>
                                <h3 class="text-white text-2xl font-bold font-display">{{ $settings['app_name'] ?? 'Toko Roti' }}</h3>
                                <div class="flex items-center gap-1 mt-2">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg class="w-4 h-4 text-amber-200 fill-amber-200" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                    <span class="text-white/80 text-xs ml-1">{{ number_format($stats['rating'], 1) }} dari {{ number_format($stats['customers']) }}+ ulasan</span>
                                </div>
                            </div>

                            {{-- Product list --}}
                            <div class="px-6 -mt-4">
                                @forelse($heroProducts as $product)
                                    <div class="flex items-center gap-3 py-3 border-b border-neutral-100 last:border-0">
                                        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center flex-shrink-0 text-2xl">
                                            @if($product->main_image)
                                                <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-xl" />
                                            @else
                                                🥐
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2">
                                                <p class="text-sm font-semibold text-neutral-900 truncate">{{ $product->name }}</p>
                                                @if($product->is_featured)
                                                    <span class="px-1.5 py-0.5 bg-amber-100 text-amber-700 text-[10px] font-bold rounded">Terlaris</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-neutral-400">{{ $product->category->name ?? '' }}</p>
                                        </div>
                                        <p class="text-sm font-bold text-amber-600 flex-shrink-0">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                    </div>
                                @empty
                                    <div class="py-8 text-center text-neutral-400 text-sm">Belum ada produk</div>
                                @endforelse
                            </div>

                            {{-- Footer CTA --}}
                            <div class="px-6 py-4 bg-amber-50 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span class="text-xs text-neutral-600 font-medium">Gratis ongkir min. Rp {{ number_format($settings['free_shipping_min'] ?? 200000, 0, ',', '.') }}</span>
                                </div>
                                <a href="{{ route('products.index') }}" wire:navigate class="text-xs font-bold text-amber-600 hover:text-amber-700">
                                    Lihat Semua →
                                </a>
                            </div>
                            </div>
                        @endif

                        {{-- Floating badge: rating --}}
                        <div class="absolute -top-5 -right-5 bg-white rounded-2xl shadow-xl px-4 py-3 border border-amber-100 animate-float">
                            <div class="flex items-center gap-2">
                                <div class="w-9 h-9 bg-amber-500 rounded-xl flex items-center justify-center">
                                    <span class="text-lg">⭐</span>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-neutral-900">Top Rated</p>
                                    <p class="text-xs text-neutral-500">{{ number_format($stats['rating'], 1) }}/5.0</p>
                                </div>
                            </div>
                        </div>

                        {{-- Floating badge: order --}}
                        <div class="absolute -bottom-5 -left-5 bg-amber-500 rounded-2xl shadow-xl px-4 py-3 animate-float" style="animation-delay: 1.2s">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                                    <span class="text-base">🛒</span>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-white">{{ number_format($stats['customers']) }}+ Pelanggan</p>
                                    <p class="text-[10px] text-amber-100">Sudah mempercayai kami</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== FEATURES BAR ===== --}}
    <section class="bg-white border-y border-neutral-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-0 md:divide-x divide-neutral-100">
                @foreach([
                    ['icon' => '🌾', 'title' => 'Bahan Premium', 'desc' => 'Dipilih dengan cermat'],
                    ['icon' => '🔥', 'title' => 'Dipanggang Segar', 'desc' => 'Setiap pagi hari'],
                    ['icon' => '🚚', 'title' => 'Pengiriman Cepat', 'desc' => 'Sampai ke rumah Anda'],
                    ['icon' => '💯', 'title' => 'Garansi Kualitas', 'desc' => 'Atau uang kembali'],
                ] as $feature)
                    <div class="flex items-center gap-3 px-4 py-2">
                        <span class="text-2xl flex-shrink-0">{{ $feature['icon'] }}</span>
                        <div>
                            <p class="text-sm font-semibold text-neutral-900">{{ $feature['title'] }}</p>
                            <p class="text-xs text-neutral-500">{{ $feature['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== PRODUCTS SECTION (tabbed) ===== --}}
    <section id="products" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
             x-data="{ tab: 'popular' }">

            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                <div>
                    <p class="text-amber-600 text-sm font-semibold uppercase tracking-wider mb-2">Produk Kami</p>
                    <h2 class="section-title">Pilihan Terbaik</h2>
                </div>
                <a href="{{ route('products.index') }}" wire:navigate
                   class="hidden sm:flex items-center gap-1.5 text-sm font-medium text-amber-600 hover:text-amber-700 transition-colors">
                    Lihat Semua
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            {{-- Tabs --}}
            <div class="flex gap-1 bg-neutral-100 p-1 rounded-xl w-fit mb-8">
                <button @click="tab = 'popular'"
                        :class="tab === 'popular' ? 'bg-white shadow text-neutral-900' : 'text-neutral-500 hover:text-neutral-700'"
                        class="px-5 py-2 rounded-lg text-sm font-medium transition-all">
                    🔥 Terpopuler
                </button>
                <button @click="tab = 'new'"
                        :class="tab === 'new' ? 'bg-white shadow text-neutral-900' : 'text-neutral-500 hover:text-neutral-700'"
                        class="px-5 py-2 rounded-lg text-sm font-medium transition-all">
                    ✨ Terbaru
                </button>
                <button @click="tab = 'category'"
                        :class="tab === 'category' ? 'bg-white shadow text-neutral-900' : 'text-neutral-500 hover:text-neutral-700'"
                        class="px-5 py-2 rounded-lg text-sm font-medium transition-all">
                    📂 Kategori
                </button>
            </div>

            {{-- Tab: Terpopuler --}}
            <div x-show="tab === 'popular'" x-transition>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                    @forelse($featuredProducts as $product)
                        <div class="product-card group">
                            <div class="relative">
                                <a href="{{ route('products.show', $product->slug) }}" wire:navigate
                                   class="block relative overflow-hidden bg-amber-50" style="aspect-ratio: 4/3;">
                                    @if($product->main_image)
                                        <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="img-smooth group-hover:scale-110" />
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-5xl">🥐</div>
                                    @endif
                                    <div class="absolute top-2 left-2 flex flex-col gap-1">
                                        @if(!$product->in_stock)
                                            <span class="badge badge-out">Habis</span>
                                        @elseif($product->low_stock)
                                            <span class="badge badge-sale">Stok Tipis</span>
                                        @endif
                                    </div>
                                </a>
                                @auth('customer')
                                    <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                                        <livewire:customer.wishlist.wishlist-button :product-id="$product->id" :key="'fp-wish-'.$product->id" />
                                    </div>
                                @endauth
                            </div>
                            <div class="p-4">
                                <p class="text-xs text-amber-600 font-medium mb-1">{{ $product->category->name ?? '' }}</p>
                                <a href="{{ route('products.show', $product->slug) }}" wire:navigate>
                                    <h3 class="font-semibold text-neutral-900 text-sm mb-2 line-clamp-2 hover:text-amber-600 transition-colors">{{ $product->name }}</h3>
                                </a>
                                @if($product->rating_average > 0)
                                    <div class="flex items-center gap-1 mb-3">
                                        <div class="flex">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-3.5 h-3.5 {{ $i <= round($product->rating_average) ? 'text-amber-400' : 'text-neutral-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @endfor
                                        </div>
                                        <span class="text-xs text-neutral-500">({{ $product->review_count }})</span>
                                    </div>
                                @endif
                                <div class="flex items-center justify-between gap-2">
                                    <p class="font-bold text-neutral-900 text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                    @auth('customer')
                                        @if($product->in_stock)
                                            <livewire:customer.cart.add-to-cart :product-id="$product->id" :key="'fp-cart-'.$product->id" />
                                        @else
                                            <span class="text-xs text-neutral-400">Habis</span>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" wire:navigate class="text-xs text-amber-600 hover:text-amber-700 font-medium">Login</a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-4 text-center py-12 text-neutral-400">
                            <span class="text-4xl block mb-3">🥐</span>
                            <p>Belum ada produk tersedia</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Tab: Terbaru --}}
            <div x-show="tab === 'new'" x-transition>
                @if($newProducts->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6">
                        @foreach($newProducts as $product)
                            <div class="product-card group">
                                <div class="relative">
                                    <a href="{{ route('products.show', $product->slug) }}" wire:navigate
                                       class="block relative overflow-hidden bg-amber-50" style="aspect-ratio: 4/3;">
                                        <span class="absolute top-2 left-2 z-10 badge badge-new">✨ Baru</span>
                                        @if($product->main_image)
                                            <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="img-smooth group-hover:scale-110" />
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-5xl">🥐</div>
                                        @endif
                                    </a>
                                    @auth('customer')
                                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                                            <livewire:customer.wishlist.wishlist-button :product-id="$product->id" :key="'np-wish-'.$product->id" />
                                        </div>
                                    @endauth
                                </div>
                                <div class="p-4">
                                    <a href="{{ route('products.show', $product->slug) }}" wire:navigate>
                                        <h3 class="font-semibold text-neutral-900 text-sm mb-1 line-clamp-2 hover:text-amber-600 transition-colors">{{ $product->name }}</h3>
                                    </a>
                                    <div class="flex items-center justify-between gap-2 mt-2">
                                        <p class="font-bold text-amber-600 text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                        @auth('customer')
                                            @if($product->in_stock)
                                                <livewire:customer.cart.add-to-cart :product-id="$product->id" :key="'np-cart-'.$product->id" />
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}" wire:navigate class="text-xs text-amber-600 hover:text-amber-700 font-medium">Login</a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 text-neutral-400">
                        <span class="text-4xl block mb-3">✨</span>
                        <p>Belum ada produk baru</p>
                    </div>
                @endif
            </div>

            {{-- Tab: Kategori --}}
            <div x-show="tab === 'category'" x-transition>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                    @forelse($categories as $category)
                        <a href="{{ route('products.index', ['category' => $category->id]) }}" wire:navigate
                           class="group bg-white rounded-2xl border border-neutral-100 p-4 text-center hover:border-amber-300 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                            <div class="aspect-square rounded-xl overflow-hidden bg-amber-50 mb-3 relative">
                                <div class="w-full h-full flex items-center justify-center">
                                    @if($category->getFirstMediaUrl('icon'))
                                        <img src="{{ $category->getFirstMediaUrl('icon') }}" alt="{{ $category->name }}" class="w-full h-full object-cover" />
                                    @else
                                        <span class="text-4xl">🍞</span>
                                    @endif
                                </div>
                                @if($category->products_count > 0)
                                    <span class="absolute top-1.5 right-1.5 w-5 h-5 bg-amber-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                                        {{ $category->products_count > 9 ? '9+' : $category->products_count }}
                                    </span>
                                @endif
                            </div>
                            <h3 class="text-sm font-semibold text-neutral-800 group-hover:text-amber-600 transition-colors line-clamp-2">{{ $category->name }}</h3>
                        </a>
                    @empty
                        <div class="col-span-6 text-center py-8 text-neutral-400">Belum ada kategori</div>
                    @endforelse
                </div>
            </div>

        </div>
    </section>

    {{-- ===== PROMO BANNER ===== --}}
    @if($vouchers->count() > 0)
    <section class="py-8 bg-cream-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-amber-500 to-amber-600 rounded-3xl overflow-hidden">
                <div class="flex flex-col md:flex-row items-center justify-between px-8 py-10 gap-6">
                    <div class="text-center md:text-left">
                        <span class="inline-block px-3 py-1 bg-white/20 text-white text-xs font-bold rounded-full mb-3 uppercase tracking-wider">Penawaran Spesial</span>
                        <h3 class="font-display text-2xl sm:text-3xl font-bold text-white mb-2">{{ $vouchers->first()->name }}</h3>
                        <p class="text-amber-100 text-sm">{{ $vouchers->first()->description }}</p>
                        <div class="mt-3 inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-lg border border-white/20">
                            <span class="text-white text-xs font-medium">Kode:</span>
                            <span class="text-white text-sm font-bold tracking-wider">{{ $vouchers->first()->code }}</span>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        @guest('customer')
                            <a href="{{ route('register') }}" wire:navigate class="inline-flex items-center gap-2 bg-white text-amber-600 font-bold px-8 py-3.5 rounded-xl hover:bg-amber-50 transition-colors shadow-lg">
                                Daftar Gratis
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                        @else
                            <a href="{{ route('products.index') }}" wire:navigate class="inline-flex items-center gap-2 bg-white text-amber-600 font-bold px-8 py-3.5 rounded-xl hover:bg-amber-50 transition-colors shadow-lg">
                                Belanja Sekarang
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- ===== PROMOTIONS (Bundle & Package) ===== --}}
    @if($promotions->count() > 0)
    <section class="py-12 bg-white" x-data="{ open: false, promo: {} }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <p class="text-amber-600 text-sm font-semibold uppercase tracking-wider mb-2">Penawaran Terbatas</p>
                <h2 class="text-2xl font-bold text-neutral-900">Promo & Paket Spesial</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($promotions as $promo)
                <div
                    class="bg-gradient-to-br from-amber-50 to-cream-100 border border-amber-200 rounded-2xl p-6 flex flex-col cursor-pointer hover:shadow-md hover:-translate-y-0.5 transition-all"
                    @click="promo = {{ json_encode([
                        'name'         => $promo->name,
                        'description'  => $promo->description,
                        'type'         => $promo->type === 'package' ? 'Paket' : 'Bundle',
                        'price'        => 'Rp ' . number_format($promo->price, 0, ',', '.'),
                        'min_quantity' => $promo->min_quantity,
                        'valid_until'  => $promo->valid_until?->format('d M Y'),
                        'items'        => $promo->items->map(fn($i) => [
                            'name' => $i->product->name,
                            'qty'  => $i->quantity,
                        ])->values()->toArray(),
                    ]) }}; open = true"
                >
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-xs font-bold px-2 py-1 rounded-full bg-amber-100 text-amber-700">
                            {{ $promo->type === 'package' ? 'Paket' : 'Bundle' }}
                        </span>
                        @if($promo->valid_until)
                            <span class="text-[10px] text-neutral-400">s/d {{ $promo->valid_until->format('d M Y') }}</span>
                        @endif
                    </div>
                    <h3 class="font-bold text-neutral-900 text-base mb-1">{{ $promo->name }}</h3>
                    @if($promo->description)
                        <p class="text-neutral-500 text-sm mb-3 line-clamp-2">{{ $promo->description }}</p>
                    @endif
                    <p class="text-xs text-neutral-400 mb-4">{{ $promo->items->count() }} produk dalam promo ini</p>
                    <div class="mt-auto flex items-center justify-between">
                        <p class="text-xl font-bold text-amber-600">Rp {{ number_format($promo->price, 0, ',', '.') }}</p>
                        <span class="text-xs font-semibold text-amber-600 underline underline-offset-2">Lihat Detail →</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Detail Modal --}}
        <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
             @keydown.escape.window="open = false">
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="open = false"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 z-10" x-transition>
                <button @click="open = false" class="absolute top-4 right-4 text-neutral-400 hover:text-neutral-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                <span class="text-xs font-bold px-2 py-1 rounded-full bg-amber-100 text-amber-700 mb-3 inline-block" x-text="promo.type"></span>
                <h3 class="text-xl font-bold text-neutral-900 mb-1" x-text="promo.name"></h3>
                <p class="text-sm text-neutral-500 mb-4" x-text="promo.description"></p>

                <div class="border-t border-neutral-100 pt-4 mb-4">
                    <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-2">Produk dalam Promo</p>
                    <ul class="space-y-2">
                        <template x-for="item in promo.items" :key="item.name">
                            <li class="flex items-center justify-between text-sm">
                                <span class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                    <span x-text="item.name" class="text-neutral-700"></span>
                                </span>
                                <span class="text-neutral-400 text-xs" x-text="'×' + item.qty"></span>
                            </li>
                        </template>
                    </ul>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-neutral-100">
                    <div>
                        <p class="text-xs text-neutral-400">Harga Promo</p>
                        <p class="text-2xl font-bold text-amber-600" x-text="promo.price"></p>
                        <p class="text-xs text-neutral-400 mt-0.5" x-show="promo.valid_until">
                            Berlaku s/d <span x-text="promo.valid_until"></span>
                        </p>
                    </div>
                    <a href="{{ route('products.index') }}" wire:navigate
                       class="btn-primary px-6 py-2.5 text-sm">
                        Pesan Sekarang
                    </a>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- ===== TESTIMONIALS ===== --}}
    @if($reviews->count() > 0)
    <section class="py-16 bg-amber-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <p class="text-amber-600 text-sm font-semibold uppercase tracking-wider mb-2">Ulasan Pelanggan</p>
                <h2 class="section-title">Apa Kata Mereka?</h2>
                <p class="section-subtitle mx-auto mt-3">Ribuan pelanggan sudah merasakan kelezatan produk kami</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($reviews as $review)
                    <div class="bg-white rounded-2xl p-6 border border-amber-100 hover:shadow-lg transition-shadow">
                        {{-- Stars --}}
                        <div class="flex gap-0.5 mb-4">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-neutral-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                            @endfor
                        </div>

                        @if($review->review)
                            <p class="text-neutral-600 text-sm leading-relaxed mb-5 line-clamp-3">"{{ $review->review }}"</p>
                        @endif

                        <div class="flex items-center gap-3 pt-4 border-t border-neutral-100">
                            <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-amber-600 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-sm font-bold text-white">{{ strtoupper(substr($review->customer->name ?? 'A', 0, 1)) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-neutral-900">{{ $review->customer->name ?? 'Pelanggan' }}</p>
                                <p class="text-xs text-neutral-500 truncate">{{ $review->product->name ?? '' }}</p>
                            </div>
                            <span class="text-xs text-neutral-400">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ===== INFO LINKS ===== --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach([
                    ['route' => 'about', 'emoji' => '🏪', 'title' => 'Tentang Kami', 'desc' => 'Cerita dan misi kami dalam menghadirkan roti berkualitas', 'cta' => 'Pelajari Lebih Lanjut'],
                    ['route' => 'contact', 'emoji' => '💬', 'title' => 'Hubungi Kami', 'desc' => 'Punya pertanyaan? Tim kami siap membantu Anda 24/7', 'cta' => 'Kontak Kami'],
                    ['route' => 'faq', 'emoji' => '❓', 'title' => 'FAQ', 'desc' => 'Temukan jawaban atas pertanyaan yang sering diajukan', 'cta' => 'Lihat FAQ'],
                ] as $info)
                    <a href="{{ route($info['route']) }}" class="group card-hover p-6 flex flex-col items-center text-center">
                        <div class="w-16 h-16 bg-amber-50 group-hover:bg-amber-100 rounded-2xl flex items-center justify-center mb-4 transition-colors">
                            <span class="text-3xl">{{ $info['emoji'] }}</span>
                        </div>
                        <h3 class="font-semibold text-neutral-900 mb-2">{{ $info['title'] }}</h3>
                        <p class="text-sm text-neutral-500 mb-4 leading-relaxed">{{ $info['desc'] }}</p>
                        <span class="text-sm font-medium text-amber-600 group-hover:text-amber-700 flex items-center gap-1 transition-colors">
                            {{ $info['cta'] }}
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

</div>
