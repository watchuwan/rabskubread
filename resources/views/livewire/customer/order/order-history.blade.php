<div class="min-h-screen bg-cream-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-ui.page-header title="Riwayat Pesanan" description="Pantau status dan detail pesanan Anda" />

        <!-- Status Filter -->
        <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 p-4 mb-6">
            <div class="flex flex-wrap gap-2">
                <button 
                    wire:click="$set('statusFilter', '')"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $statusFilter === '' ? 'bg-cream-500 text-neutral-900' : 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200' }}"
                >
                    Semua
                </button>
                <button 
                    wire:click="$set('statusFilter', 'pending')"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $statusFilter === 'pending' ? 'bg-cream-500 text-neutral-900' : 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200' }}"
                >
                    Menunggu
                </button>
                <button 
                    wire:click="$set('statusFilter', 'processing')"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $statusFilter === 'processing' ? 'bg-cream-500 text-neutral-900' : 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200' }}"
                >
                    Diproses
                </button>
                <button 
                    wire:click="$set('statusFilter', 'shipped')"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $statusFilter === 'shipped' ? 'bg-cream-500 text-neutral-900' : 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200' }}"
                >
                    Dikirim
                </button>
                <button 
                    wire:click="$set('statusFilter', 'completed')"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $statusFilter === 'completed' ? 'bg-cream-500 text-neutral-900' : 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200' }}"
                >
                    Selesai
                </button>
                <button 
                    wire:click="$set('statusFilter', 'cancelled')"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $statusFilter === 'cancelled' ? 'bg-cream-500 text-neutral-900' : 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200' }}"
                >
                    Dibatalkan
                </button>
            </div>
        </div>

        @if($orders->count() > 0)
            <div class="space-y-4">
                @foreach($orders as $order)
                    <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden">
                        <!-- Order Header -->
                        <div class="bg-neutral-50 px-6 py-4 border-b border-neutral-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-neutral-600">Pesanan #{{ $order->order_number }}</p>
                                    <p class="text-xs text-neutral-500 mt-1">
                                        {{ $order->created_at->format('d M Y, H:i') }}
                                    </p>
                                </div>
                                <div>
                                    <x-ui.order-status-badge :status="$order->status" size="sm" />
                                </div>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($order->items as $item)
                                    <div class="flex gap-4">
                                        <div class="w-20 h-20 bg-neutral-100 rounded-lg overflow-hidden flex-shrink-0">
                                            <x-ui.product-image :src="$item->product->main_image" :alt="$item->product->name" fallback-size="text-2xl" />
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-neutral-900">{{ $item->product->name }}</h3>
                                            <p class="text-sm text-neutral-500">
                                                {{ $item->quantity }} x Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                            </p>
                                            <p class="text-sm font-medium text-neutral-900 mt-1">
                                                Subtotal: Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Order Total -->
                            <div class="border-t border-neutral-200 mt-6 pt-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-neutral-600">Total Pesanan</p>
                                        <p class="text-xs text-neutral-500 mt-1">
                                            {{ $order->items->sum('quantity') }} item
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-2xl font-bold text-cream-600">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-3 mt-6">
                                <a
                                    href="{{ route('orders.show', $order->id) }}"
                                    class="btn-primary flex-1 text-center"
                                    wire:navigate
                                >
                                    <svg class="w-5 h-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Lihat Detail
                                </a>

                                @if($order->status === 'pending')
                                    <a
                                        href="#"
                                        class="btn-primary"
                                    >
                                        Bayar Sekarang
                                    </a>
                                @endif

                                @if($order->status === 'completed')
                                    <button class="btn-secondary px-4 py-2">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $orders->links('vendor.livewire.simple-tailwind') }}
            </div>
        @else
            <x-ui.empty-state
                icon="📦"
                title="Belum Ada Pesanan"
                description="Mulai belanja dan buat pesanan pertama Anda"
                :href="route('products.index')"
                label="Belanja Sekarang"
            />
        @endif
    </div>
</div>
