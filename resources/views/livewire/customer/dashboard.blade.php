<div class="min-h-screen bg-cream-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-neutral-900 mb-2">
                Halo, {{ auth()->guard('customer')->user()->name }}! 👋
            </h1>
            <p class="text-neutral-600">Selamat datang di dashboard Anda</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
            <div class="card p-4">
                <div class="flex flex-col">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <p class="text-2xl font-bold text-neutral-900">{{ $stats['total_orders'] }}</p>
                    <p class="text-xs text-neutral-600">Total Pesanan</p>
                </div>
            </div>

            <div class="card p-4">
                <div class="flex flex-col">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-2xl font-bold text-neutral-900">{{ $stats['pending_orders'] }}</p>
                    <p class="text-xs text-neutral-600">Pending</p>
                </div>
            </div>

            <div class="card p-4">
                <div class="flex flex-col">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                        </svg>
                    </div>
                    <p class="text-2xl font-bold text-neutral-900">{{ $stats['processing_orders'] }}</p>
                    <p class="text-xs text-neutral-600">Diproses</p>
                </div>
            </div>

            <div class="card p-4">
                <div class="flex flex-col">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-2xl font-bold text-neutral-900">{{ $stats['completed_orders'] }}</p>
                    <p class="text-xs text-neutral-600">Selesai</p>
                </div>
            </div>

            <div class="card p-4">
                <div class="flex flex-col">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-2xl font-bold text-neutral-900">{{ $stats['cancelled_orders'] }}</p>
                    <p class="text-xs text-neutral-600">Dibatalkan</p>
                </div>
            </div>

            <div class="card p-4">
                <div class="flex flex-col">
                    <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <p class="text-2xl font-bold text-neutral-900">{{ $stats['wishlist_count'] }}</p>
                    <p class="text-xs text-neutral-600">Wishlist</p>
                </div>
            </div>
        </div>

        <!-- Total Spent Card -->
        <div class="card p-6 mb-8 bg-gradient-to-r from-cream-400 to-amber-400">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-neutral-700 mb-1">Total Belanja</p>
                    <p class="text-3xl font-bold text-neutral-900">Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</p>
                    <p class="text-xs text-neutral-600 mt-1">Dari {{ $stats['completed_orders'] }} pesanan selesai</p>
                </div>
                <div class="w-16 h-16 bg-white/50 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-neutral-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Orders by Status -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Orders Section -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Pending Orders -->
                @if($pendingOrders->count() > 0)
                    <div class="card p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold text-neutral-900 flex items-center gap-2">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                                Menunggu Pembayaran
                            </h2>
                            <a href="{{ route('orders.index') }}?status=pending" class="text-sm text-cream-600 hover:text-cream-700 font-medium" wire:navigate>
                                Lihat Semua →
                            </a>
                        </div>
                        <div class="grid grid-cols-1 gap-4">
                            @foreach($pendingOrders as $order)
                                <a href="{{ route('orders.show', $order->id) }}" wire:navigate class="block p-4 border border-neutral-200 rounded-lg hover:border-yellow-400 hover:shadow-md transition-all">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-semibold text-neutral-900">{{ $order->order_number }}</span>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                                            Pending
                                        </span>
                                    </div>
                                    <p class="text-sm text-neutral-600 mb-2">{{ $order->items->count() }} item</p>
                                    <p class="text-lg font-bold text-neutral-900 mb-2">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                    <p class="text-xs text-neutral-400">{{ $order->created_at->diffForHumans() }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Processing Orders -->
                @if($processingOrders->count() > 0)
                    <div class="card p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold text-neutral-900 flex items-center gap-2">
                                <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
                                Sedang Diproses
                            </h2>
                            <a href="{{ route('orders.index') }}?status=processing" class="text-sm text-cream-600 hover:text-cream-700 font-medium" wire:navigate>
                                Lihat Semua →
                            </a>
                        </div>
                        <div class="grid grid-cols-1 gap-4">
                            @foreach($processingOrders as $order)
                                <a href="{{ route('orders.show', $order->id) }}" wire:navigate class="block p-4 border border-neutral-200 rounded-lg hover:border-purple-400 hover:shadow-md transition-all">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-semibold text-neutral-900">{{ $order->order_number }}</span>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-700">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-neutral-600 mb-2">{{ $order->items->count() }} item</p>
                                    <p class="text-lg font-bold text-neutral-900 mb-2">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                    <p class="text-xs text-neutral-400">{{ $order->created_at->diffForHumans() }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Completed Orders -->
                @if($completedOrders->count() > 0)
                    <div class="card p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold text-neutral-900 flex items-center gap-2">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                Pesanan Selesai
                            </h2>
                            <a href="{{ route('orders.index') }}?status=completed" class="text-sm text-cream-600 hover:text-cream-700 font-medium" wire:navigate>
                                Lihat Semua →
                            </a>
                        </div>
                        <div class="grid grid-cols-1 gap-4">
                            @foreach($completedOrders as $order)
                                <a href="{{ route('orders.show', $order->id) }}" wire:navigate class="block p-4 border border-neutral-200 rounded-lg hover:border-green-400 hover:shadow-md transition-all">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-semibold text-neutral-900">{{ $order->order_number }}</span>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                            Selesai
                                        </span>
                                    </div>
                                    <p class="text-sm text-neutral-600 mb-2">{{ $order->items->count() }} item</p>
                                    <p class="text-lg font-bold text-neutral-900 mb-2">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                    <p class="text-xs text-neutral-400">{{ $order->created_at->diffForHumans() }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Empty State -->
                @if($pendingOrders->count() === 0 && $processingOrders->count() === 0 && $completedOrders->count() === 0)
                    <div class="card p-12 text-center">
                        <svg class="w-20 h-20 text-neutral-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <h3 class="text-xl font-semibold text-neutral-900 mb-2">Belum Ada Pesanan</h3>
                        <p class="text-neutral-600 mb-6">Mulai belanja sekarang dan temukan produk favorit Anda!</p>
                        <a href="{{ route('products.index') }}" class="btn-primary inline-flex items-center gap-2" wire:navigate>
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Mulai Belanja
                        </a>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Quick Actions -->
                <div class="card p-6">
                    <h2 class="text-lg font-bold text-neutral-900 mb-4">Aksi Cepat</h2>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('orders.index') }}" class="flex flex-col items-center gap-2 p-4 rounded-lg hover:bg-cream-50 transition-colors" wire:navigate>
                            <div class="w-12 h-12 bg-cream-400 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-neutral-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-neutral-700 text-center">Pesanan</span>
                        </a>

                        <a href="{{ route('products.index') }}" class="flex flex-col items-center gap-2 p-4 rounded-lg hover:bg-cream-50 transition-colors" wire:navigate>
                            <div class="w-12 h-12 bg-cream-400 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-neutral-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-neutral-700 text-center">Belanja</span>
                        </a>

                        <a href="{{ route('wishlist.index') }}" class="flex flex-col items-center gap-2 p-4 rounded-lg hover:bg-cream-50 transition-colors" wire:navigate>
                            <div class="w-12 h-12 bg-cream-400 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-neutral-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-neutral-700 text-center">Wishlist</span>
                        </a>

                        <a href="{{ route('addresses.index') }}" class="flex flex-col items-center gap-2 p-4 rounded-lg hover:bg-cream-50 transition-colors" wire:navigate>
                            <div class="w-12 h-12 bg-cream-400 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-neutral-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-neutral-700 text-center">Alamat</span>
                        </a>

                        <a href="{{ route('profile.index') }}" class="flex flex-col items-center gap-2 p-4 rounded-lg hover:bg-cream-50 transition-colors" wire:navigate>
                            <div class="w-12 h-12 bg-cream-400 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-neutral-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-neutral-700 text-center">Profil</span>
                        </a>
                    </div>
                </div>

                <!-- Account Info -->
                <div class="card p-6">
                    <h2 class="text-lg font-bold text-neutral-900 mb-4">Informasi Akun</h2>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-cream-400 rounded-full flex items-center justify-center">
                                <span class="text-lg font-bold text-neutral-900">
                                    {{ strtoupper(substr(auth()->guard('customer')->user()->name, 0, 1)) }}
                                </span>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-neutral-900">{{ auth()->guard('customer')->user()->name }}</p>
                                <p class="text-sm text-neutral-600">{{ auth()->guard('customer')->user()->email }}</p>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-neutral-200">
                            <div class="flex items-center justify-between text-sm mb-2">
                                <span class="text-neutral-600">Bergabung Sejak</span>
                                <span class="font-medium text-neutral-900">{{ auth()->guard('customer')->user()->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-neutral-600">Total Pesanan</span>
                                <span class="font-medium text-neutral-900">{{ $stats['total_orders'] }}</span>
                            </div>
                        </div>
                        <a href="{{ route('profile.index') }}" class="block text-center btn-secondary text-sm w-full" wire:navigate>
                            Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
