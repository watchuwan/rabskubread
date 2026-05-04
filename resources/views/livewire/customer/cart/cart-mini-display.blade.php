<div class="relative" x-data="{ open: @entangle('isOpen') }" @click.outside="open = false">
    <!-- Cart Icon -->
    <button 
        @click="open = !open"
        class="relative p-2 rounded-lg hover:bg-neutral-100 transition-colors"
        wire:navigate
    >
        <svg class="w-6 h-6 text-neutral-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        
        @if($itemsCount > 0)
            <span class="absolute -top-1 -right-1 w-5 h-5 bg-cream-400 text-neutral-900 text-xs font-bold rounded-full flex items-center justify-center">
                {{ $itemsCount }}
            </span>
        @endif
    </button>

    <!-- Cart Dropdown -->
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-neutral-200 overflow-hidden z-50"
        style="display: none;"
    >
        @if(auth()->guard('customer')->check())
            @if($itemsCount > 0)
                <!-- Cart Items Header -->
                <div class="p-4 border-b border-neutral-200 bg-neutral-50">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-neutral-900">Keranjang Belanja</h3>
                        <span class="text-sm text-neutral-600">{{ $itemsCount }} item</span>
                    </div>
                </div>

                <!-- Cart Items Preview -->
                <div class="max-h-64 overflow-y-auto">
                    @foreach($cartItems as $item)
                        <div class="p-4 flex gap-3 border-b border-neutral-100 hover:bg-neutral-50">
                            <div class="w-16 h-16 bg-neutral-100 rounded-lg overflow-hidden flex-shrink-0">
                                @if($item->product->main_image)
                                    <img
                                        src="{{ $item->product->main_image }}"
                                        alt="{{ $item->product->name }}"
                                        class="w-full h-full object-cover"
                                    />
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-2xl">
                                        🥐
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-neutral-900 truncate mb-1">
                                    {{ $item->product->name }}
                                </p>
                                
                                <!-- Quantity Controls -->
                                <div class="flex items-center gap-2 mb-1">
                                    <!-- Decrease Button -->
                                    <button
                                        wire:click="decreaseQuantity({{ $item->id }})"
                                        class="w-7 h-7 flex items-center justify-center rounded bg-neutral-200 hover:bg-neutral-300 border border-neutral-300 transition-colors"
                                        title="Kurangi quantity"
                                    >
                                        <svg class="w-4 h-4 text-neutral-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                        </svg>
                                    </button>
                                    
                                    <!-- Quantity Display -->
                                    <span class="text-sm font-bold text-neutral-900 w-8 text-center bg-neutral-100 px-2 py-1 rounded">
                                        {{ $item->quantity }}
                                    </span>
                                    
                                    <!-- Increase Button -->
                                    <button
                                        wire:click="increaseQuantity({{ $item->id }})"
                                        class="w-7 h-7 flex items-center justify-center rounded bg-neutral-200 hover:bg-neutral-300 border border-neutral-300 transition-colors"
                                        title="Tambah quantity"
                                    >
                                        <svg class="w-4 h-4 text-neutral-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                    
                                    <!-- Delete Button -->
                                    <button
                                        wire:click="removeItem({{ $item->id }})"
                                        class="w-7 h-7 flex items-center justify-center rounded bg-red-100 hover:bg-red-200 border border-red-300 transition-colors"
                                        title="Hapus item"
                                    >
                                        <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                                
                                <!-- Price -->
                                <p class="text-xs text-neutral-500">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach

                    @if($itemsCount > 3)
                        <div class="p-3 text-center text-sm text-neutral-600 bg-neutral-50">
                            + {{ $itemsCount - 3 }} item lainnya
                        </div>
                    @endif
                </div>

                <!-- Cart Summary & Actions -->
                <div class="p-4 border-t border-neutral-200 bg-neutral-50">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm text-neutral-600">Subtotal</span>
                        <span class="text-lg font-bold text-neutral-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <a href="{{ route('cart.index') }}" class="block w-full btn-primary text-center mb-2" wire:navigate>
                        Lihat Keranjang
                    </a>
                    <a href="{{ route('checkout') }}" class="block w-full btn-secondary text-center" wire:navigate>
                        Checkout
                    </a>
                </div>
            @else
                <!-- Empty Cart -->
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 text-neutral-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <p class="text-neutral-600 mb-4">Keranjang Anda kosong</p>
                    <a href="{{ route('products.index') }}" class="inline-block btn-primary" wire:navigate>
                        Mulai Belanja
                    </a>
                </div>
            @endif
        @else
            <!-- Not Logged In -->
            <div class="p-8 text-center">
                <svg class="w-12 h-12 text-neutral-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                <p class="text-neutral-600 mb-4">Login untuk melihat keranjang</p>
                <a href="{{ route('login') }}" class="inline-block btn-primary" wire:navigate>
                    Masuk Sekarang
                </a>
            </div>
        @endif
    </div>
</div>
