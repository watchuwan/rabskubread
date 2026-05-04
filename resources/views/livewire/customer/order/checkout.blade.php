@php
    use Illuminate\Support\Facades\Storage;
@endphp

<div class="min-h-screen bg-cream-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-neutral-900 mb-2">Checkout</h1>
            <p class="text-neutral-600">Selesaikan pesanan Anda dengan mudah dan aman</p>
        </div>

        <form wire:submit.prevent="placeOrder">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Checkout Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Shipping Address -->
                    <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-semibold text-neutral-900">
                                <svg class="w-6 h-6 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Alamat Pengiriman
                            </h2>
                            <a
                                href="{{ route('addresses.create') }}"
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-sm border border-neutral-300 rounded-lg hover:bg-neutral-50 transition-colors"
                                wire:navigate
                            >
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Alamat
                            </a>
                        </div>

                        @if($addresses->count() > 0)
                            <div class="space-y-3">
                                @foreach($addresses as $address)
                                    <label class="block cursor-pointer">
                                        <input
                                            type="radio"
                                            wire:model.live="selectedAddress"
                                            value="{{ $address->id }}"
                                            class="sr-only peer"
                                        />
                                        <div class="p-4 border-2 border-neutral-200 rounded-xl peer-checked:border-cream-400 peer-checked:bg-cream-50 hover:border-cream-300 transition-all">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <h3 class="font-semibold text-neutral-900">{{ $address->label }}</h3>
                                                        @if($address->is_default)
                                                            <span class="px-2 py-0.5 bg-cream-400 text-neutral-900 text-xs font-semibold rounded">
                                                                Default
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <p class="text-sm text-neutral-600">
                                                        {{ $address->street_address }}
                                                    </p>
                                                    <p class="text-sm text-neutral-600">
                                                        {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}
                                                    </p>
                                                    <p class="text-sm text-neutral-600 mt-1 flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                        </svg>
                                                        {{ $address->phone }}
                                                    </p>
                                                </div>
                                                <svg
                                                    class="w-6 h-6 text-cream-500 {{ $selectedAddress === $address->id ? 'opacity-100' : 'opacity-0' }}"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                >
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <div class="mt-4">
                                <a
                                    href="{{ route('addresses.index') }}"
                                    class="text-sm text-cream-600 hover:text-cream-700 font-medium"
                                    wire:navigate
                                >
                                    Kelola Alamat Lainnya →
                                </a>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 text-neutral-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <p class="text-neutral-600 mb-4">Belum ada alamat pengiriman</p>
                                <a
                                    href="{{ route('addresses.create') }}"
                                    class="btn-primary inline-flex items-center gap-2"
                                    wire:navigate
                                >
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Tambah Alamat Sekarang
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Shipping Method -->
                    <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 p-6">
                        <h2 class="text-xl font-semibold text-neutral-900 mb-4">
                            <svg class="w-6 h-6 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                            </svg>
                            Metode Pengiriman
                        </h2>

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
                            @error('selectedShippingMethod')
                                <p class="mt-2 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        @else
                            <p class="text-neutral-600">Tidak ada metode pengiriman tersedia</p>
                        @endif
                    </div>

                    <!-- Order Notes -->
                    <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 p-6">
                        <h2 class="text-xl font-semibold text-neutral-900 mb-4">
                            <svg class="w-6 h-6 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            Catatan Pesanan (Opsional)
                        </h2>
                        <textarea
                            rows="3"
                            placeholder="Tambahkan catatan khusus untuk pesanan Anda (misal: jangan terlalu manis)"
                            class="w-full border border-neutral-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cream-500"
                        ></textarea>
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 p-6">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input
                                type="checkbox"
                                wire:model.live="agreeTerms"
                                class="w-5 h-5 text-cream-600 border-neutral-300 rounded focus:ring-cream-500 mt-0.5"
                            />
                            <span class="text-sm text-neutral-600">
                                Saya setuju dengan
                                <a href="#" class="text-cream-600 hover:text-cream-700 underline">Syarat & Ketentuan</a>
                                dan
                                <a href="#" class="text-cream-600 hover:text-cream-700 underline">Kebijakan Privasi</a>
                                yang berlaku
                            </span>
                        </label>
                        @error('agreeTerms')
                            <p class="mt-2 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 p-6 sticky top-20">
                        <h2 class="text-lg font-semibold text-neutral-900 mb-6">Ringkasan Pesanan</h2>

                        <!-- Items -->
                        <div class="space-y-3 mb-6 max-h-48 overflow-y-auto">
                            @foreach($cartItems as $item)
                                <div class="flex gap-3">
                                    <div class="w-16 h-16 bg-neutral-100 rounded-lg overflow-hidden flex-shrink-0">
                                        <x-ui.product-image :src="$item->product->main_image" :alt="$item->product->name" fallback-size="text-xl" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-neutral-900 truncate">{{ $item->product->name }}</p>
                                        <p class="text-xs text-neutral-500">{{ $item->quantity }} x Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Totals -->
                        <div class="space-y-3 mb-6 pt-4 border-t border-neutral-200">
                            <div class="flex justify-between text-sm text-neutral-600">
                                <span>Subtotal</span>
                                <span class="font-medium">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-neutral-600">
                                <span>Ongkos Kirim</span>
                                <span class="font-medium">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                            </div>
                            @if($promotionDiscount > 0)
                            <div class="flex justify-between text-sm text-success">
                                <span>Diskon Promosi</span>
                                <span class="font-medium">- Rp {{ number_format($promotionDiscount, 0, ',', '.') }}</span>
                            </div>
                            @endif
                            @if($discount > 0)
                            <div class="flex justify-between text-sm text-success">
                                <span>Diskon Voucher <span class="text-xs font-normal">({{ strtoupper($voucherCode) }})</span></span>
                                <span class="font-medium">- Rp {{ number_format($discount, 0, ',', '.') }}</span>
                            </div>
                            @endif
                            <div class="border-t border-neutral-200 pt-3"></div>
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
                                    type="button"
                                    wire:click="applyVoucher"
                                    class="btn-secondary text-sm py-2"
                                >
                                    Pakai
                                </button>
                            </div>
                        </div>

                        <!-- Place Order Button -->
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            @if(!$selectedAddress || !$selectedShippingMethod || !$agreeTerms) disabled @endif
                            class="btn-primary w-full py-3 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span wire:loading.remove wire:target="placeOrder">
                                <svg class="w-5 h-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Lanjut ke Pembayaran
                            </span>
                            <span wire:loading wire:target="placeOrder" class="flex items-center gap-2">
                                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memproses...
                            </span>
                        </button>

                        @if(!$selectedAddress || !$agreeTerms)
                            <p class="mt-3 text-xs text-center text-neutral-500">
                                @if(!$selectedAddress)
                                    Pilih alamat pengiriman terlebih dahulu
                                @elseif(!$agreeTerms)
                                    Setujui syarat & ketentuan untuk melanjutkan
                                @endif
                            </p>
                        @else
                            <p class="mt-3 text-xs text-center text-neutral-500">
                                Anda akan diarahkan ke halaman pembayaran Midtrans
                            </p>
                        @endif

                        <!-- Trust Badges -->
                        <div class="mt-6 space-y-3 text-sm text-neutral-600">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <span>Pembayaran Aman</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Garansi Kepuasan</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                </svg>
                                <span>Pengiriman Terpercaya</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
