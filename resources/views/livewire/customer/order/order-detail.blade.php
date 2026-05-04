<div class="min-h-screen bg-cream-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-ui.breadcrumb :items="[
            ['label' => 'Pesanan', 'href' => route('orders.index'), 'navigate' => true],
            ['label' => '#' . $order->order_number],
        ]" />

        <!-- Order Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-neutral-900 mb-2">
                        Pesanan #{{ $order->order_number }}
                    </h1>
                    <p class="text-sm text-neutral-600">
                        {{ $order->created_at->format('d M Y, H:i') }}
                    </p>
                </div>
                <x-ui.order-status-badge :status="$order->status" />
            </div>

            @if($order->status === 'pending')
                <div class="bg-warning/10 border border-warning p-4 rounded-xl mt-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-warning flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <h3 class="font-semibold text-warning mb-1">Menunggu Pembayaran</h3>
                            <p class="text-sm text-neutral-600">
                                Silakan selesaikan pembayaran dalam waktu 24 jam sebelum pesanan dibatalkan.
                            </p>
                            <a href="{{ route('orders.payment', $order->id) }}" class="btn-primary mt-3 inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                Bayar Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Order Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Items -->
                <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden">
                    <div class="p-6 border-b border-neutral-200">
                        <h2 class="text-lg font-semibold text-neutral-900">Produk</h2>
                    </div>
                    <div class="divide-y divide-neutral-200">
                        @foreach($order->items as $item)
                            <div class="p-6 flex gap-4">
                                <div class="w-24 h-24 bg-neutral-100 rounded-lg overflow-hidden flex-shrink-0">
                                    <x-ui.product-image :src="$item->product->main_image" :alt="$item->product->name" />
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-neutral-900 mb-1">
                                        {{ $item->product->name }}
                                    </h3>
                                    <p class="text-sm text-neutral-500 mb-2">
                                        {{ $item->product->category->name }}
                                    </p>
                                    <div class="flex items-center gap-4 text-sm text-neutral-600">
                                        <span>{{ $item->quantity }} x Rp {{ number_format($item->unit_price, 0, ',', '.') }}</span>
                                    </div>
                                    <p class="text-sm font-medium text-neutral-900 mt-2">
                                        Subtotal: Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Shipping Address -->
                @if($order->shippingAddress)
                    <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 p-6">
                        <h2 class="text-lg font-semibold text-neutral-900 mb-4">
                            <svg class="w-6 h-6 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Alamat Pengiriman
                        </h2>
                        <div class="space-y-2 text-sm text-neutral-600">
                            <p class="font-medium text-neutral-900">{{ $order->shippingAddress->label }}</p>
                            <p>{{ $order->shippingAddress->street_address }}</p>
                            <p>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->postal_code }}</p>
                            <p><svg class="w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg> {{ $order->shippingAddress->phone }}</p>
                        </div>
                    </div>
                @endif

                <!-- Payment Information -->
                <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 p-6">
                    <h2 class="text-lg font-semibold text-neutral-900 mb-4">
                        <svg class="w-6 h-6 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        Informasi Pembayaran
                    </h2>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-neutral-600">Metode Pembayaran</span>
                            <span class="font-medium text-neutral-900">{{ $order->payment->payment_number ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-neutral-600">Status Pembayaran</span>
                            <span class="font-medium {{ $order->payment_status === 'success' ? 'text-success' : 'text-warning' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                        @if($order->paid_at)
                            <div class="flex justify-between text-sm">
                                <span class="text-neutral-600">Dibayar Pada</span>
                                <span class="font-medium text-neutral-900">{{ $order->paid_at->format('d M Y, H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Order Timeline -->
                <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 p-6">
                    <h2 class="text-lg font-semibold text-neutral-900 mb-4">
                        <svg class="w-6 h-6 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Timeline Pesanan
                    </h2>
                    <div class="space-y-4">
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-success flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </div>
                            <div class="flex-1 pb-4">
                                <p class="font-medium text-neutral-900">Pesanan Dibuat</p>
                                <p class="text-sm text-neutral-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>

                        @if($order->paid_at)
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full bg-success flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                </div>
                                <div class="flex-1 pb-4">
                                    <p class="font-medium text-neutral-900">Pembayaran Diterima</p>
                                    <p class="text-sm text-neutral-500">{{ $order->paid_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($order->shipped_at)
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full bg-success flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                </div>
                                <div class="flex-1 pb-4">
                                    <p class="font-medium text-neutral-900">Pesanan Dikirim</p>
                                    <p class="text-sm text-neutral-500">{{ $order->shipped_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($order->completed_at)
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full bg-success flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-neutral-900">Pesanan Selesai</p>
                                    <p class="text-sm text-neutral-500">{{ $order->completed_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 p-6 sticky top-20">
                    <h2 class="text-lg font-semibold text-neutral-900 mb-6">Ringkasan Pesanan</h2>

                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm text-neutral-600">
                            <span>Subtotal</span>
                            <span class="font-medium">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-neutral-600">
                            <span>Pajak (11%)</span>
                            <span class="font-medium">Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-neutral-600">
                            <span>Ongkos Kirim</span>
                            <span class="font-medium">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        @if($order->discount > 0)
                            <div class="flex justify-between text-sm text-success">
                                <span>Diskon</span>
                                <span class="font-medium">- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <div class="border-t border-neutral-200 pt-3"></div>

                        <div class="flex justify-between text-lg font-bold text-neutral-900">
                            <span>Total</span>
                            <span class="text-cream-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        @if($order->status === 'completed')
                            <button class="btn-primary w-full" wire:click="reorder">
                                <svg class="w-5 h-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Pesan Lagi
                            </button>
                        @endif

                        @if($order->status === 'pending')
                            <a href="{{ route('orders.payment', $order->id) }}" class="btn-primary w-full text-center block" wire:navigate>
                                <svg class="w-5 h-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                Bayar Sekarang
                            </a>
                        @endif

                        @if(in_array($order->status, ['pending', 'processing']))
                            <button
                                @click="$dispatch('open-modal', 'cancel-order')"
                                class="btn-secondary w-full text-danger border-danger hover:bg-danger-50"
                            >
                                <svg class="w-5 h-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Batalkan Pesanan
                            </button>
                        @endif

                        @if($order->status !== 'cancelled')
                            <button wire:click="downloadInvoice" class="btn-secondary w-full">
                                <svg class="w-5 h-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Unduh Invoice
                            </button>
                        @endif
                    </div>

                    <!-- Help Section -->
                    <div class="mt-6 pt-6 border-t border-neutral-200">
                        <p class="text-sm font-medium text-neutral-900 mb-2">Butuh Bantuan?</p>
                        <p class="text-xs text-neutral-600 mb-3">
                            Hubungi kami jika ada masalah dengan pesanan Anda
                        </p>
                        <a href="#" class="text-sm text-cream-600 hover:text-cream-700 font-medium">
                            Hubungi Customer Service →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Order Modal -->
    <div
        x-data="{ show: false }"
        @open-modal.window="if ($event.detail === 'cancel-order') show = true"
        @close-modal.window="if ($event.detail === 'cancel-order') show = false"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <!-- Backdrop -->
        <div
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="show = false"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm"
        ></div>

        <!-- Modal -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                @click.stop
                class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6"
            >
                <!-- Icon -->
                <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full">
                    <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <!-- Title -->
                <h3 class="text-lg font-semibold text-neutral-900 text-center mb-2">
                    Batalkan Pesanan?
                </h3>

                <!-- Message -->
                <p class="text-sm text-neutral-600 text-center mb-6">
                    Pesanan #{{ $order->order_number }} akan dibatalkan dan stok produk akan dikembalikan. Tindakan ini tidak dapat dibatalkan.
                </p>

                <!-- Actions -->
                <div class="flex gap-3">
                    <button
                        @click="show = false"
                        class="flex-1 px-4 py-2.5 text-sm font-medium text-neutral-700 bg-neutral-100 hover:bg-neutral-200 rounded-lg transition-colors"
                    >
                        Tidak, Kembali
                    </button>
                    <button
                        wire:click="cancelOrder"
                        @click="show = false"
                        class="flex-1 px-4 py-2.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors"
                    >
                        Ya, Batalkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
