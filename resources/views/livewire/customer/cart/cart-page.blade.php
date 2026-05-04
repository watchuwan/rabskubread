<div class="min-h-screen bg-cream-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-neutral-900 mb-2">Keranjang Belanja</h1>
            <p class="text-neutral-600">Review pesanan Anda sebelum melanjutkan ke pembayaran</p>
        </div>

        @if($cartItems->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden">
                        <div class="p-6 border-b border-neutral-200">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-neutral-900">
                                    {{ $cartItems->count() }} Produk di Keranjang
                                </h2>
                                <button
                                    wire:click="confirmClearCart"
                                    class="text-sm text-danger hover:text-danger-700 font-medium transition-colors"
                                >
                                    Hapus Semua
                                </button>
                            </div>
                        </div>

                        <div class="divide-y divide-neutral-200">
                            @foreach($cartItems as $item)
                                <div class="p-6 flex gap-4">
                                    <!-- Product Image -->
                                    <div class="w-24 h-24 flex-shrink-0 bg-neutral-100 rounded-lg overflow-hidden">
                                        <x-ui.product-image :src="$item->product->main_image" :alt="$item->product->name" />
                                    </div>

                                    <!-- Product Info -->
                                    <div class="flex-1">
                                        <div class="flex justify-between mb-2">
                                            <div>
                                                <h3 class="font-semibold text-neutral-900 mb-1">
                                                    {{ $item->product->name }}
                                                </h3>
                                                <p class="text-sm text-neutral-500">
                                                    {{ $item->product->category->name }}
                                                </p>
                                            </div>
                                            <button
                                                wire:click="confirmDelete({{ $item->id }}, '{{ $item->product->name }}')"
                                                class="text-neutral-400 hover:text-danger transition-colors"
                                                title="Hapus item"
                                            >
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Quantity Controls -->
                                        <div class="flex items-center justify-between mt-4">
                                            <div class="flex items-center gap-2">
                                                <button
                                                    wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                                    class="w-8 h-8 flex items-center justify-center rounded bg-neutral-200 hover:bg-neutral-300 border border-neutral-300 transition-colors"
                                                    title="Kurangi quantity"
                                                >
                                                    <svg class="w-4 h-4 text-neutral-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                    </svg>
                                                </button>
                                                
                                                <span class="w-12 text-center font-bold text-neutral-900 bg-neutral-100 px-3 py-2 rounded">
                                                    {{ $item->quantity }}
                                                </span>
                                                
                                                <button
                                                    wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                                    class="w-8 h-8 flex items-center justify-center rounded bg-neutral-200 hover:bg-neutral-300 border border-neutral-300 transition-colors"
                                                    title="Tambah quantity"
                                                >
                                                    <svg class="w-4 h-4 text-neutral-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                </button>
                                            </div>

                                            <!-- Price -->
                                            <div class="text-right">
                                                <p class="text-lg font-bold text-neutral-900">
                                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                                </p>
                                                <p class="text-xs text-neutral-500">
                                                    Rp {{ number_format($item->product->price, 0, ',', '.') }} x {{ $item->quantity }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Stock Info -->
                                        @if(!$item->isInStock())
                                            <p class="text-sm text-danger mt-2">
                                                Stok tidak mencukupi (tersedia: {{ $item->product->stock }})
                                            </p>
                                        @elseif($item->product->low_stock)
                                            <p class="text-sm text-warning mt-2">
                                                Stok menipis!
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Continue Shopping -->
                    <a
                        href="{{ route('products.index') }}"
                        class="inline-flex items-center gap-2 text-cream-600 hover:text-cream-700 font-medium"
                        wire:navigate
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Lanjut Belanja
                    </a>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 p-6 sticky top-20">
                        <h2 class="text-lg font-semibold text-neutral-900 mb-6">Ringkasan Pesanan</h2>

                        <!-- Shipping Method Selection -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-neutral-700 mb-3">
                                Metode Pengiriman
                            </label>
                            
                            @if($shippingMethods->count() > 0)
                                @if($shippingMethods->count() === 1 && $shippingMethods->first()->code === 'PICKUP')
                                    <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                        <p class="text-sm text-blue-800">
                                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                            </svg>
                                            Produk ini hanya tersedia untuk diambil di toko
                                        </p>
                                    </div>
                                @endif
                                
                                <div class="space-y-3">
                                    @foreach($shippingMethods as $method)
                                        <label class="block cursor-pointer">
                                            <input
                                                type="radio"
                                                value="{{ $method->id }}"
                                                name="selectedShippingMethod"
                                                wire:model.live="selectedShippingMethod"
                                                class="sr-only peer"
                                            />
                                            <div class="border-2 border-neutral-200 rounded-xl p-4 peer-checked:border-cream-500 peer-checked:bg-cream-50 hover:border-cream-300 transition-all">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex-1">
                                                        <p class="font-semibold text-neutral-900">{{ $method->name }}</p>
                                                        <p class="text-sm text-neutral-600 mt-1">{{ $method->description }}</p>
                                                        @if($method->code === 'PICKUP')
                                                            <p class="text-xs text-neutral-500 mt-1">Siap diambil setelah pesanan selesai</p>
                                                        @elseif($method->estimated_days > 0)
                                                            <p class="text-xs text-neutral-500 mt-1">Estimasi: {{ $method->estimated_days }} hari</p>
                                                        @else
                                                            <p class="text-xs text-neutral-500 mt-1">Estimasi: Hari ini</p>
                                                        @endif
                                                    </div>
                                                    <div class="text-right ml-4">
                                                        @if($method->cost > 0)
                                                            <p class="font-bold text-cream-600">Rp {{ number_format($method->cost, 0, ',', '.') }}</p>
                                                        @else
                                                            <p class="font-bold text-green-600">GRATIS</p>
                                                        @endif
                                                        <svg
                                                            class="w-6 h-6 text-cream-500 {{ $selectedShippingMethod === $method->id ? 'opacity-100' : 'opacity-0' }}"
                                                            fill="currentColor"
                                                            viewBox="0 0 20 20"
                                                        >
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-neutral-600 text-sm">Tidak ada metode pengiriman tersedia</p>
                            @endif
                        </div>

                        <div class="space-y-4 mb-6">
                            <!-- Subtotal -->
                            <div class="flex justify-between text-neutral-600">
                                <span>Subtotal</span>
                                <span class="font-medium">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>

                            <!-- Tax -->
                            <div class="flex justify-between text-neutral-600">
                                <span>Pajak (11%)</span>
                                <span class="font-medium">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                            </div>

                            <!-- Shipping -->
                            <div class="flex justify-between text-neutral-600">
                                <span>Ongkos Kirim</span>
                                <span class="font-medium">
                                    @if($shippingCost == 0)
                                        <span class="text-success">Gratis</span>
                                    @else
                                        Rp {{ number_format($shippingCost, 0, ',', '.') }}
                                    @endif
                                </span>
                            </div>

                            @if($promotionDiscount > 0)
                            <div class="flex justify-between text-success">
                                <span>Diskon Promosi</span>
                                <span class="font-medium">- Rp {{ number_format($promotionDiscount, 0, ',', '.') }}</span>
                            </div>
                            @endif

                            @if($discount > 0)
                            <div class="flex justify-between text-success">
                                <span>Diskon Voucher <span class="text-xs font-normal">({{ strtoupper($voucherCode) }})</span></span>
                                <span class="font-medium">- Rp {{ number_format($discount, 0, ',', '.') }}</span>
                            </div>
                            @endif

                            <!-- Divider -->
                            <div class="border-t border-neutral-200"></div>

                            <!-- Total -->
                            <div class="flex justify-between text-lg font-bold text-neutral-900">
                                <span>Total</span>
                                <span class="text-cream-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Voucher -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-neutral-700 mb-2">
                                Kode Voucher
                            </label>
                            <div class="flex gap-2">
                                <input
                                    wire:model="voucherCode"
                                    placeholder="Masukkan kode voucher"
                                    class="flex-1 border border-neutral-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cream-500"
                                />
                                <button
                                    wire:click="applyVoucher"
                                    class="btn-secondary text-sm py-2"
                                >
                                    Pakai
                                </button>
                            </div>
                        </div>

                        <!-- Checkout Button -->
                        <a
                            href="{{ route('checkout') }}"
                            class="btn-primary block w-full py-3 mb-4 text-center"
                            wire:navigate
                        >
                            Lanjut ke Pembayaran
                        </a>

                        <!-- Trust Badges -->
                        <div class="space-y-3 text-sm text-neutral-600">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <span>Pembayaran Aman</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                </svg>
                                <span>Pengiriman Terpercaya</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Garansi Kepuasan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 p-12 text-center">
                <div class="text-6xl mb-4">🛒</div>
                <h3 class="text-xl font-semibold text-neutral-900 mb-2">Keranjang Anda Kosong</h3>
                <p class="text-neutral-600 mb-6">
                    Mulai belanja dan isi keranjang Anda dengan produk favorit
                </p>
                <a
                    href="{{ route('products.index') }}"
                    class="btn-primary inline-flex items-center gap-2"
                    wire:navigate
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Belanja Sekarang
                </a>
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div
        x-data="{
            isOpen: false,
            init() {
                Livewire.on('open-delete-modal', () => {
                    this.isOpen = true;
                });
                Livewire.on('close-delete-modal', () => {
                    this.isOpen = false;
                });
            }
        }"
        x-show="isOpen"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <!-- Backdrop -->
        <div
            x-show="isOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm"
        ></div>

        <!-- Modal Panel -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div
                x-show="isOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6"
                @click.away="isOpen = false"
            >
                <!-- Icon -->
                <div class="mx-auto w-16 h-16 bg-danger/10 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-danger" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>

                <!-- Title -->
                <h3 class="text-xl font-bold text-neutral-900 text-center mb-2">
                    Hapus Produk?
                </h3>

                <!-- Description -->
                <p class="text-neutral-600 text-center mb-6">
                    Apakah Anda yakin ingin menghapus
                    <span class="font-semibold text-neutral-900" x-text="$wire.productNameToDelete ?? 'produk ini'"></span>
                    dari keranjang?
                </p>

                <!-- Actions -->
                <div class="grid grid-cols-2 gap-3">
                    <button
                        @click="isOpen = false"
                        class="px-4 py-3 border border-neutral-300 rounded-xl text-neutral-700 font-medium hover:bg-neutral-50 transition-colors"
                    >
                        Batal
                    </button>
                    <button
                        wire:click="removeItem"
                        @click="isOpen = false"
                        class="px-4 py-3 bg-danger text-white rounded-xl font-medium hover:bg-danger-600 transition-colors"
                    >
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Clear Cart Modal -->
    <div
        x-data="{
            isOpen: false,
            init() {
                $watch('$wire.showClearCartModal', value => {
                    this.isOpen = value;
                });
            }
        }"
        x-show="isOpen"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <!-- Backdrop -->
        <div
            x-show="isOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm"
        ></div>

        <!-- Modal Panel -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div
                x-show="isOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6"
                @click.away="$wire.showClearCartModal = false"
            >
                <!-- Icon -->
                <div class="mx-auto w-16 h-16 bg-danger/10 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-danger" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>

                <!-- Title -->
                <h3 class="text-xl font-bold text-neutral-900 text-center mb-2">
                    Kosongkan Keranjang?
                </h3>

                <!-- Description -->
                <p class="text-neutral-600 text-center mb-6">
                    Apakah Anda yakin ingin menghapus
                    <span class="font-semibold text-neutral-900">{{ $cartItems->count() }} produk</span>
                    dari keranjang? Tindakan ini tidak dapat dibatalkan.
                </p>

                <!-- Actions -->
                <div class="grid grid-cols-2 gap-3">
                    <button
                        @click="$wire.showClearCartModal = false"
                        class="px-4 py-3 border border-neutral-300 rounded-xl text-neutral-700 font-medium hover:bg-neutral-50 transition-colors"
                    >
                        Batal
                    </button>
                    <button
                        wire:click="clearCart"
                        @click="$wire.showClearCartModal = false"
                        class="px-4 py-3 bg-danger text-white rounded-xl font-medium hover:bg-danger-600 transition-colors"
                    >
                        Ya, Hapus Semua
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
