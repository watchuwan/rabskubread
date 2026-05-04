<div class="min-h-screen bg-cream-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-ui.page-header title="Produk Kami" description="Temukan berbagai pilihan roti segar dan lezat untuk Anda" />

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Filters Sidebar -->
            <div class="lg:w-64 flex-shrink-0">
                <div class="card p-6 sticky top-20">
                    <h3 class="text-lg font-semibold text-neutral-900 mb-4">Filter</h3>
                    
                    <!-- Search -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-neutral-700 mb-2">
                            Cari Produk
                        </label>
                        <div class="relative">
                            <input 
                                wire:model.live.debounce.300ms="search"
                                type="text"
                                placeholder="Nama produk..."
                                class="w-full pl-10 pr-4 py-2 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                            />
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-neutral-700 mb-2">
                            Kategori
                        </label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" wire:model.live="category" value="" class="w-4 h-4 text-amber-600" />
                                <span class="text-sm {{ !$category ? 'font-semibold text-amber-600' : 'text-neutral-700' }}">Semua</span>
                            </label>
                            @foreach($this->categories as $cat)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" wire:model.live="category" value="{{ $cat->id }}" class="w-4 h-4 text-amber-600" />
                                    <span class="text-sm {{ $category == $cat->id ? 'font-semibold text-amber-600' : 'text-neutral-700' }}">{{ $cat->name }} ({{ $cat->products_count }})</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-neutral-700 mb-2">
                            Rentang Harga
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <input 
                                wire:model.live.debounce.300ms="minPrice"
                                type="number"
                                placeholder="Min"
                                class="text-sm px-3 py-2 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                            />
                            <input 
                                wire:model.live.debounce.300ms="maxPrice"
                                type="number"
                                placeholder="Max"
                                class="text-sm px-3 py-2 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                            />
                        </div>
                    </div>

                    <!-- Stock Filter -->
                    <div class="mb-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:click="toggleStock" @checked(!empty($inStock)) class="w-4 h-4 text-amber-600 border-neutral-300 rounded focus:ring-amber-500" />
                            <span class="text-sm text-neutral-700">Hanya yang tersedia</span>
                        </label>
                    </div>

                    <!-- Promotion Filter -->
                    @if($promotions->count() > 0)
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-neutral-900 mb-3">Promo & Paket</h3>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" wire:model.live="promotion" value="" class="w-4 h-4 text-amber-600 border-neutral-300 focus:ring-amber-500" />
                                <span class="text-sm text-neutral-700">Semua Produk</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" wire:model.live="promotion" value="bundle" class="w-4 h-4 text-amber-600 border-neutral-300 focus:ring-amber-500" />
                                <span class="text-sm text-neutral-700">🎁 Bundle</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" wire:model.live="promotion" value="package" class="w-4 h-4 text-amber-600 border-neutral-300 focus:ring-amber-500" />
                                <span class="text-sm text-neutral-700">📦 Paket</span>
                            </label>
                        </div>
                    </div>
                    @endif

                    <!-- Clear Filters -->
                    @if($search || $category || $minPrice || $maxPrice || $inStock || $promotion)
                        <button wire:click="clearFilters" class="btn-secondary w-full text-sm">
                            <svg class="w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            Reset Filter
                        </button>
                    @endif
                </div>
            </div>

            <!-- Products Grid -->
            <div class="flex-1">
                <!-- Toolbar -->
                <div class="card p-4 mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <p class="text-sm text-neutral-600">
                            Menampilkan <span class="font-semibold text-neutral-900">{{ $products->firstItem() ?? 0 }}</span> 
                            - <span class="font-semibold text-neutral-900">{{ $products->lastItem() ?? 0 }}</span> 
                            dari <span class="font-semibold text-neutral-900">{{ $products->total() }}</span> produk
                        </p>

                        <div class="flex items-center gap-2">
                            <label class="text-sm text-neutral-600">Urutkan:</label>
                            <select wire:model.live="sortBy" class="text-sm border-neutral-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                                <option value="latest">Terbaru</option>
                                <option value="popular">Terpopuler</option>
                                <option value="rating">Rating Tertinggi</option>
                                <option value="price_asc">Harga Terendah</option>
                                <option value="price_desc">Harga Tertinggi</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Products -->
                @if($products->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
                        @foreach($products as $product)
                            <div class="product-card group">
                                <div class="relative">
                                    <a href="{{ route('products.show', $product->slug) }}" wire:navigate class="block relative overflow-hidden bg-amber-50" style="aspect-ratio: 4/3;">
                                        @if($product->main_image)
                                            <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="img-smooth group-hover:scale-110" />
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-5xl">🥐</div>
                                        @endif

                                        @if(!$product->in_stock)
                                            <span class="absolute top-2 left-2 badge badge-out">Habis</span>
                                        @elseif($product->low_stock)
                                            <span class="absolute top-2 left-2 badge badge-sale">Stok Tipis</span>
                                        @endif
                                        @php $activePromo = $product->promotionItems->first()?->promotion; @endphp
                                        @if($activePromo)
                                            <span class="absolute bottom-2 left-2 text-[10px] font-bold px-1.5 py-0.5 rounded bg-amber-500 text-white">
                                                {{ $activePromo->type === 'bundle' ? '🎁 Bundle' : '📦 Paket' }}
                                            </span>
                                        @endif
                                    </a>

                                    @auth('customer')
                                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                                            <livewire:customer.wishlist.wishlist-button :product-id="$product->id" :key="'wish-'.$product->id" />
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
                                                    <svg class="w-3.5 h-3.5 {{ $i <= round($product->rating_average) ? 'text-amber-400' : 'text-neutral-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                                @endfor
                                            </div>
                                            <span class="text-xs text-neutral-500">({{ $product->review_count }})</span>
                                        </div>
                                    @endif

                                    <div class="flex items-center justify-between gap-2">
                                        <p class="font-bold text-neutral-900 text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                        @auth('customer')
                                            @if($product->in_stock)
                                                <livewire:customer.cart.add-to-cart :product-id="$product->id" :key="'cart-'.$product->id" />
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}" wire:navigate class="text-xs text-amber-600 hover:text-amber-700 font-medium">Login</a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="empty-state">
                        <span class="text-6xl mb-4">🔍</span>
                        <h3 class="text-xl font-semibold text-neutral-900 mb-2">Produk Tidak Ditemukan</h3>
                        <p class="text-neutral-500 mb-6">Coba ubah filter atau kata kunci pencarian Anda</p>
                        @if($search || $category || $minPrice || $maxPrice || $inStock)
                            <button wire:click="clearFilters" class="btn-primary">
                                Reset Filter
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
