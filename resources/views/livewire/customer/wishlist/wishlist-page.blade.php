<div class="min-h-screen bg-cream-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-ui.page-header title="Wishlist Saya" description="Produk favorit yang ingin Anda beli" />

        @if($wishlistItems->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($wishlistItems as $item)
                    <div class="card group hover:shadow-lg transition-shadow duration-300">
                        <!-- Product Image -->
                        <div class="relative">
                            <a href="{{ route('products.show', $item->product->slug) }}" class="block" wire:navigate>
                                <div class="relative aspect-square overflow-hidden rounded-t-lg bg-neutral-100">
                                    <x-ui.product-image
                                        :src="$item->product->main_image"
                                        :alt="$item->product->name"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                        fallback-size="text-6xl"
                                    />
                                    
                                    <!-- Stock Badge -->
                                    @if(!$item->product->in_stock)
                                        <span class="absolute top-2 left-2 px-2 py-1 bg-danger text-white text-xs font-semibold rounded">
                                            Habis
                                        </span>
                                    @endif
                                </div>
                            </a>
                            
                            <!-- Remove Button -->
                            <button
                                @click="window.dispatchEvent(new CustomEvent('confirm-modal', { 
                                    detail: { 
                                        title: 'Hapus dari Wishlist?',
                                        message: 'Produk ini akan dihapus dari wishlist Anda.',
                                        confirmText: 'Ya, Hapus',
                                        cancelText: 'Batal',
                                        onConfirm: 'confirm-remove-{{ $item->id }}'
                                    }
                                }))"
                                @confirm-remove-{{ $item->id }}.window="$wire.removeItem({{ $item->id }})"
                                class="absolute top-2 right-2 p-2 bg-white rounded-full shadow-md hover:bg-red-50 hover:text-white transition-colors z-10"
                                title="Hapus dari Wishlist"
                            >
                                <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        
                        <!-- Product Info -->
                        <div class="p-4">
                            <a href="{{ route('products.show', $item->product->slug) }}" wire:navigate>
                                <p class="text-xs text-cream-700 font-medium mb-1">
                                    {{ $item->product->category->name }}
                                </p>
                                <h3 class="font-semibold text-neutral-900 mb-2 line-clamp-2 hover:text-cream-700 transition-colors">
                                    {{ $item->product->name }}
                                </h3>
                            </a>
                            
                            @if($item->product->rating_average > 0)
                                <div class="flex items-center gap-1 mb-2">
                                    <x-ui.star-rating :rating="$item->product->rating_average" />
                                    <span class="text-sm text-neutral-600">{{ number_format($item->product->rating_average, 1) }}</span>
                                </div>
                            @endif
                            
                            <!-- Price & Actions -->
                            <div class="space-y-2">
                                <p class="text-lg font-bold text-neutral-900">
                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                </p>
                                
                                <div class="flex gap-2">
                                    @if($item->product->in_stock)
                                        <button
                                            wire:click="moveToCart({{ $item->id }})"
                                            class="btn-primary flex-1 text-sm py-2"
                                        >
                                            <svg class="w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            Beli
                                        </button>
                                    @else
                                        <span class="flex-1 text-center text-xs text-neutral-400 py-2">
                                            Stok Habis
                                        </span>
                                    @endif

                                    <button
                                        @click="window.dispatchEvent(new CustomEvent('confirm-modal', { 
                                            detail: { 
                                                title: 'Hapus dari Wishlist?',
                                                message: 'Produk ini akan dihapus dari wishlist Anda.',
                                                confirmText: 'Ya, Hapus',
                                                cancelText: 'Batal',
                                                onConfirm: 'confirm-remove-bottom-{{ $item->id }}'
                                            }
                                        }))"
                                        @confirm-remove-bottom-{{ $item->id }}.window="$wire.removeItem({{ $item->id }})"
                                        class="btn-secondary px-3 py-2"
                                        title="Hapus"
                                    >
                                        <svg class="w-4 h-4 text-neutral-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Continue Shopping -->
            <div class="mt-8 text-center">
                <a 
                    href="{{ route('products.index') }}"
                    class="btn-primary inline-flex items-center gap-2"
                    wire:navigate
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    Lanjut Belanja
                </a>
            </div>
        @else
            <x-ui.empty-state
                icon="❤️"
                title="Wishlist Anda Kosong"
                description="Tambahkan produk favorit Anda ke wishlist untuk akses lebih cepat"
                :href="route('products.index')"
                label="Jelajahi Produk"
            />
        @endif
    </div>
</div>
