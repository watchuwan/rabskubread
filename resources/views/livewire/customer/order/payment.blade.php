<div class="min-h-screen bg-cream-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-neutral-900 mb-2">Pembayaran</h1>
            <p class="text-neutral-600">Selesaikan pembayaran untuk pesanan #{{ $order->order_number }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Payment Action -->
            <div class="lg:col-span-2">
                <div class="card p-6">
                    <h2 class="text-xl font-semibold text-neutral-900 mb-6">Metode Pembayaran</h2>

                    <div class="p-4 bg-cream-50 border border-cream-400 rounded-xl mb-6">
                        <div class="flex items-center gap-3">
                            <svg class="w-8 h-8 text-cream-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            <div>
                                <h3 class="font-semibold text-neutral-900">Pembayaran Online</h3>
                                <p class="text-sm text-neutral-600">Powered by Midtrans</p>
                            </div>
                        </div>
                    </div>

                    @if($snapToken)
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-800">
                            Klik tombol di bawah untuk membuka halaman pembayaran. Anda dapat memilih metode pembayaran yang tersedia.
                        </div>

                        <button
                            id="pay-button"
                            class="btn-primary w-full py-3 text-base"
                        >
                            <svg class="w-5 h-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            Bayar Sekarang — Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </button>
                    @else
                        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-xl text-sm text-yellow-800">
                            Token pembayaran tidak tersedia. Silakan hubungi dukungan atau coba lagi.
                        </div>
                        <a href="{{ route('orders.show', $order->id) }}" wire:navigate class="btn-secondary w-full text-center mt-4 block">
                            Kembali ke Detail Pesanan
                        </a>
                    @endif
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="card p-6 sticky top-20">
                    <h2 class="text-lg font-semibold text-neutral-900 mb-4">Ringkasan Pesanan</h2>

                    <div class="mb-4 pb-4 border-b border-neutral-200">
                        <p class="text-sm text-neutral-600 mb-1">Nomor Pesanan</p>
                        <p class="font-bold text-neutral-900">{{ $order->order_number }}</p>
                    </div>

                    <div class="space-y-3 mb-4 max-h-48 overflow-y-auto">
                        @foreach($order->items as $item)
                            <div class="flex gap-3">
                                <div class="w-12 h-12 bg-neutral-100 rounded-lg overflow-hidden flex-shrink-0">
                                    <x-ui.product-image :src="$item->product->main_image" :alt="$item->product->name" fallback-size="text-xl" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-neutral-900 truncate">{{ $item->product->name }}</p>
                                    <p class="text-xs text-neutral-500">{{ $item->quantity }} x Rp {{ number_format($item->unit_price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-2 pt-4 border-t border-neutral-200">
                        <div class="flex justify-between text-sm text-neutral-600">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-neutral-600">
                            <span>Pajak</span>
                            <span>Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-neutral-600">
                            <span>Ongkir</span>
                            <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold text-neutral-900 pt-2 border-t border-neutral-200">
                            <span>Total</span>
                            <span class="text-cream-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-xs text-neutral-600">
                            ⏱ Pesanan akan dibatalkan jika pembayaran tidak diterima dalam 24 jam
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($snapToken)
    @push('scripts')
        <script src="{{ $snapUrl }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
        <script>
            document.getElementById('pay-button').addEventListener('click', function () {
                window.snap.pay('{{ $snapToken }}', {
                    onSuccess: function (result) {
                        window.location.href = '{{ route('orders.show', $order->id) }}';
                    },
                    onPending: function (result) {
                        window.location.href = '{{ route('orders.show', $order->id) }}';
                    },
                    onError: function (result) {
                        alert('Pembayaran gagal. Silakan coba lagi.');
                    },
                    onClose: function () {
                        // user closed popup without paying
                    }
                });
            });
        </script>
    @endpush
@endif
