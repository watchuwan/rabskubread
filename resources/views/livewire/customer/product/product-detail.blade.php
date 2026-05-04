<div class="min-h-screen bg-cream-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-ui.breadcrumb :items="[
            ['label' => 'Produk', 'href' => route('products.index'), 'navigate' => true],
            ['label' => $product->category->name, 'href' => route('products.index', ['category' => $product->category_id]), 'navigate' => true],
            ['label' => $product->name],
        ]" />

        <!-- Product Detail -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <!-- Product Image -->
            <div>
                <div class="card overflow-hidden mb-4">
                    <div class="aspect-square bg-neutral-100">
                        <x-ui.product-image :src="$product->main_image" :alt="$product->name" fallback-size="text-9xl" />
                    </div>
                </div>

                <!-- Thumbnail Images (if multiple) -->
                @if($product->image_count > 1)
                    <div class="grid grid-cols-4 gap-4">
                        @for($i = 0; $i < min($product->image_count, 4); $i++)
                            <button
                                wire:click="$set('selectedImage', {{ $i }})"
                                class="aspect-square rounded-lg overflow-hidden border-2 {{ $selectedImage === $i ? 'border-cream-500' : 'border-neutral-200' }} hover:border-cream-400 transition-colors"
                            >
                                <img
                                    src="{{ $product->main_image }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-cover"
                                />
                            </button>
                        @endfor
                    </div>
                @endif
            </div>

            <!-- Product Info -->
            <div>
                <div class="card p-6 lg:p-8">
                    <!-- Category & Title -->
                    <p class="text-cream-700 font-medium text-sm mb-2">
                        {{ $product->category->name }}
                    </p>
                    <h1 class="text-2xl lg:text-3xl font-bold text-neutral-900 mb-4">
                        {{ $product->name }}
                    </h1>

                    @if($product->rating_average > 0)
                        <div class="flex items-center gap-2 mb-6">
                            <x-ui.star-rating :rating="$product->rating_average" size="lg" />
                            <span class="text-neutral-600">{{ number_format($product->rating_average, 1) }}</span>
                            <span class="text-neutral-400">({{ $product->review_count }} ulasan)</span>
                        </div>
                    @endif

                    <!-- Price -->
                    <div class="mb-6">
                        <div class="flex items-baseline gap-3">
                            <span class="text-3xl font-bold text-neutral-900">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <!-- Stock Status -->
                    <div class="mb-6">
                        @if($product->in_stock)
                            <div class="flex items-center gap-2 text-success">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="font-medium">Tersedia</span>
                            </div>
                            @if($product->low_stock)
                                <p class="text-sm text-warning mt-1">
                                    Stok menipis! Segera pesan sebelum kehabisan.
                                </p>
                            @endif
                        @else
                            <div class="flex items-center gap-2 text-danger">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="font-medium">Stok Habis</span>
                            </div>
                        @endif
                    </div>

                    <!-- Description -->
                    @if($product->description)
                        <div class="mb-6">
                            <h3 class="font-semibold text-neutral-900 mb-2">Deskripsi</h3>
                            <p class="text-neutral-600 text-sm leading-relaxed">
                                {{ $product->description }}
                            </p>
                        </div>
                    @endif

                    <!-- Ingredients & Allergens -->
                    @if($product->ingredients || $product->allergens)
                        <div class="mb-6 p-4 bg-cream-50 rounded-xl">
                            @if($product->ingredients)
                                <div class="mb-3">
                                    <h4 class="font-semibold text-neutral-900 text-sm mb-1">Bahan-bahan:</h4>
                                    <p class="text-xs text-neutral-600">{{ implode(', ', $product->ingredients) }}</p>
                                </div>
                            @endif
                            @if($product->allergens)
                                <div>
                                    <h4 class="font-semibold text-neutral-900 text-sm mb-1">Alergen:</h4>
                                    <p class="text-xs text-neutral-600">{{ implode(', ', $product->allergens) }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Product Details -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        @if($product->size)
                            <div>
                                <h4 class="text-xs text-neutral-500 mb-1">Ukuran</h4>
                                <p class="text-sm font-medium text-neutral-900">{{ $product->size }}</p>
                            </div>
                        @endif
                        @if($product->calories)
                            <div>
                                <h4 class="text-xs text-neutral-500 mb-1">Kalori</h4>
                                <p class="text-sm font-medium text-neutral-900">{{ $product->calories }} kkal</p>
                            </div>
                        @endif
                        @if($product->preparation_time)
                            <div>
                                <h4 class="text-xs text-neutral-500 mb-1">Waktu Persiapan</h4>
                                <p class="text-sm font-medium text-neutral-900">{{ $product->preparation_time }} menit</p>
                            </div>
                        @endif
                        @if($product->sku)
                            <div>
                                <h4 class="text-xs text-neutral-500 mb-1">SKU</h4>
                                <p class="text-sm font-medium text-neutral-900">{{ $product->sku }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Quantity & Add to Cart -->
                    <div class="mb-4">
                        @auth('customer')
                            <div class="flex gap-4">
                                <!-- Quantity Selector -->
                                <div class="flex items-center border border-neutral-300 rounded-lg">
                                    <button
                                        wire:click="if($quantity > 1) $quantity--"
                                        class="px-4 py-3 hover:bg-neutral-100 transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                        </svg>
                                    </button>
                                    <input
                                        type="number"
                                        wire:model.live="quantity"
                                        min="1"
                                        max="{{ $product->stock }}"
                                        class="w-16 text-center border-x border-neutral-300 py-3 focus:outline-none"
                                    />
                                    <button
                                        wire:click="if($quantity < $product->stock) $quantity++"
                                        class="px-4 py-3 hover:bg-neutral-100 transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Add to Cart Button -->
                                <button
                                    wire:click="addToCart"
                                    class="btn-primary flex-1 py-3 flex items-center justify-center gap-2 {{ !$product->in_stock ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    @if(!$product->in_stock) disabled @endif
                                >
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    <span>{{ $product->in_stock ? 'Tambah ke Keranjang' : 'Stok Habis' }}</span>
                                </button>

                                <!-- Wishlist Button -->
                                <livewire:customer.wishlist.wishlist-button :product-id="$product->id" :key="'wish-detail-'.$product->id" />
                            </div>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="btn-primary block w-full py-3 text-center"
                                wire:navigate
                            >
                                <svg class="w-5 h-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Login untuk Membeli
                            </a>
                        @endauth
                    </div>

                    <!-- Additional Info -->
                    <div class="border-t border-neutral-200 pt-4 space-y-2 text-sm">
                        <div class="flex items-center gap-2 text-neutral-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                            </svg>
                            <span>Pengiriman tersedia</span>
                        </div>
                        <div class="flex items-center gap-2 text-neutral-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span>Garansi kepuasan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div id="reviews" class="mb-12">
            <livewire:customer.product.product-reviews :product="$product" />
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-neutral-900 mb-6">Produk Terkait</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="card group hover:shadow-lg transition-shadow duration-300">
                            <a href="{{ route('products.show', $relatedProduct->slug) }}" class="block" wire:navigate>
                                <div class="relative aspect-square overflow-hidden rounded-t-lg bg-neutral-100">
                                    @if($relatedProduct->main_image)
                                        <img
                                            src="{{ $relatedProduct->main_image }}"
                                            alt="{{ $relatedProduct->name }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                        />
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-6xl">
                                            🥐
                                        </div>
                                    @endif
                                </div>
                            </a>
                            <div class="p-4">
                                <a href="{{ route('products.show', $relatedProduct->slug) }}" wire:navigate>
                                    <h3 class="font-semibold text-neutral-900 mb-2 line-clamp-2 hover:text-cream-700 transition-colors">
                                        {{ $relatedProduct->name }}
                                    </h3>
                                </a>
                                <p class="text-lg font-bold text-neutral-900">
                                    Rp {{ number_format($relatedProduct->price, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
