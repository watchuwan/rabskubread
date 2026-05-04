<div class="min-h-screen bg-cream-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-ui.page-header title="Ulasan Saya" description="Kelola ulasan produk yang telah Anda tulis" />

        @if($reviews->count() > 0)
            <div class="space-y-4">
                @foreach($reviews as $review)
                    <div class="card p-6">
                        <div class="flex gap-4">
                            <div class="w-24 h-24 bg-neutral-100 rounded-lg overflow-hidden flex-shrink-0">
                                <x-ui.product-image :src="$review->product->main_image" :alt="$review->product->name" />
                            </div>

                            <!-- Review Content -->
                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <h3 class="font-semibold text-neutral-900 mb-1">{{ $review->product->name }}</h3>
                                        <x-ui.star-rating :rating="$review->rating" />
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-neutral-500">{{ $review->created_at->format('d M Y') }}</p>
                                        @if($review->is_approved)
                                            <span class="inline-block mt-1 px-2 py-0.5 bg-success/10 text-success text-xs font-semibold rounded">Disetujui</span>
                                        @else
                                            <span class="inline-block mt-1 px-2 py-0.5 bg-warning/10 text-warning text-xs font-semibold rounded">Menunggu Persetujuan</span>
                                        @endif
                                    </div>
                                </div>

                                @if($review->review)
                                    <p class="text-neutral-600 text-sm mb-3">{{ $review->review }}</p>
                                @endif

                                <!-- Admin Response -->
                                @if($review->admin_response)
                                    <div class="p-3 bg-neutral-50 rounded-lg">
                                        <p class="text-xs font-semibold text-neutral-700 mb-1">Respons dari {{ $settings['app_name'] ?? 'Toko Roti' }}:</p>
                                        <p class="text-sm text-neutral-600">{{ $review->admin_response }}</p>
                                    </div>
                                @endif

                                <!-- Actions -->
                                <div class="flex gap-2 mt-4 pt-4 border-t border-neutral-200">
                                    <button 
                                        wire:click="delete({{ $review->id }})"
                                        wire:confirm="Apakah Anda yakin ingin menghapus ulasan ini?"
                                        class="btn-secondary text-sm text-danger hover:bg-danger-50"
                                    >
                                        <svg class="w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $reviews->links('vendor.livewire.simple-tailwind') }}
            </div>
        @else
            <x-ui.empty-state
                title="Belum Ada Ulasan"
                description="Anda belum menulis ulasan untuk produk apapun"
                :href="route('products.index')"
                label="Mulai Belanja"
            />
        @endif
    </div>
</div>
