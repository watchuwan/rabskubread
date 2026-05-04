<div class="bg-white rounded-2xl shadow-sm border border-neutral-200 p-6 lg:p-8">
    <h2 class="text-2xl font-bold text-neutral-900 mb-6">Ulasan Pelanggan</h2>
    
    @if($reviews->count() > 0 || $canReview)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Rating Summary -->
            <div class="lg:col-span-1">
                <div class="text-center lg:text-left">
                    <div class="text-5xl font-bold text-neutral-900 mb-2">
                        {{ number_format($product->rating_average, 1) }}
                    </div>
                    <div class="flex items-center justify-center lg:justify-start gap-1 mb-2">
                        <x-ui.star-rating :rating="$product->rating_average" size="lg" />
                    </div>
                    <p class="text-sm text-neutral-500 mb-6">
                        Berdasarkan {{ $product->review_count }} ulasan
                    </p>
                    
                    <!-- Rating Distribution -->
                    <div class="space-y-2">
                        @foreach([5, 4, 3, 2, 1] as $star)
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-neutral-600 w-8">{{ $star }}</span>
                                <svg class="w-4 h-4 text-neutral-400" viewBox="0 0 24 24" fill="currentColor"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                <div class="flex-1 h-2 bg-neutral-100 rounded-full overflow-hidden">
                                    <div 
                                        class="h-full bg-cream-500 rounded-full"
                                        style="width: {{ $product->review_count > 0 ? ($ratingDistribution[$star] / $product->review_count * 100) : 0 }}%"
                                    ></div>
                                </div>
                                <span class="text-sm text-neutral-500 w-8 text-right">{{ $ratingDistribution[$star] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Write Review Button/Form -->
            <div class="lg:col-span-2">
                @if(!auth()->guard('customer')->check())
                    <!-- Guest View -->
                    <div class="border-2 border-dashed border-neutral-200 rounded-xl p-8 text-center">
                        <svg class="w-12 h-12 text-neutral-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <h3 class="text-lg font-semibold text-neutral-900 mb-2">
                            Login untuk Menulis Ulasan
                        </h3>
                        <p class="text-sm text-neutral-600 mb-4">
                            Hanya pelanggan yang sudah membeli produk ini yang bisa memberikan ulasan
                        </p>
                        <a href="{{ route('login') }}" class="btn-primary" wire:navigate>
                            Masuk Sekarang
                        </a>
                    </div>
                @else
                    @if($canReview)
                        @if($showForm)
                            <div class="border border-neutral-200 rounded-xl p-6">
                                <h3 class="text-lg font-semibold text-neutral-900 mb-4">
                                    Tulis Ulasan Anda
                                </h3>
                                
                                <!-- Star Rating -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-neutral-700 mb-2">
                                        Rating
                                    </label>
                                    <div class="flex gap-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <button
                                                type="button"
                                                wire:click="$set('rating', {{ $i }})"
                                                class="focus:outline-none p-0 bg-transparent border-0"
                                            >
                                                <svg class="w-8 h-8 {{ $i <= $rating ? 'text-cream-500 fill-cream-500' : 'text-neutral-300 hover:text-cream-400' }}" viewBox="0 0 24 24" fill="currentColor"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                            </button>
                                        @endfor
                                    </div>
                                </div>

                                <!-- Review Text -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-neutral-700 mb-2">
                                        Ulasan Anda
                                    </label>
                                    <textarea
                                        wire:model="review"
                                        rows="4"
                                        placeholder="Ceritakan pengalaman Anda dengan produk ini..."
                                        class="w-full border border-neutral-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cream-500 focus:border-cream-500 @error('review') border-danger @enderror"
                                    ></textarea>
                                    <div class="flex justify-between mt-1">
                                        @error('review')
                                            <p class="text-sm text-danger">{{ $message }}</p>
                                        @enderror
                                        <p class="text-xs text-neutral-500 ml-auto">
                                            {{ strlen($review) }}/1000 karakter
                                        </p>
                                    </div>
                                </div>

                                <!-- Info Box -->
                                <div class="bg-info/10 border border-info p-3 rounded-lg mb-4">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-5 h-5 text-info flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <p class="text-xs text-neutral-600">
                                            Ulasan Anda akan ditampilkan setelah disetujui oleh admin. Pastikan ulasan Anda relevan dan membantu.
                                        </p>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-3">
                                    <button
                                        wire:click="submitReview"
                                        wire:loading.attr="disabled"
                                        class="btn-primary flex-1"
                                    >
                                        <span wire:loading.remove wire:target="submitReview">Kirim Ulasan</span>
                                        <span wire:loading wire:target="submitReview">
                                            <svg class="w-5 h-5 animate-spin inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        </span>
                                    </button>
                                    <button
                                        wire:click="$set('showForm', false)"
                                        class="btn-secondary"
                                    >
                                        Batal
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="border-2 border-dashed border-neutral-200 rounded-xl p-8 text-center">
                                <svg class="w-12 h-12 text-neutral-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                <h3 class="text-lg font-semibold text-neutral-900 mb-2">
                                    Bagikan Pengalaman Anda
                                </h3>
                                <p class="text-sm text-neutral-600 mb-4">
                                    Bantu pelanggan lain dengan menulis ulasan tentang produk ini
                                </p>
                                <button
                                    wire:click="$set('showForm', true)"
                                    class="btn-primary"
                                >
                                    <svg class="w-5 h-5 inline mr-2" viewBox="0 0 24 24" fill="currentColor"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                    Tulis Ulasan
                                </button>
                            </div>
                        @endif
                    @else
                        <div class="border-2 border-dashed border-neutral-200 rounded-xl p-8 text-center">
                            <svg class="w-12 h-12 text-neutral-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <h3 class="text-lg font-semibold text-neutral-900 mb-2">
                                Sudah Pernah Membeli?
                            </h3>
                            <p class="text-sm text-neutral-600">
                                Login dan tulis ulasan jika Anda sudah membeli produk ini
                            </p>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    @endif
    
    <!-- Reviews List -->
    @if($reviews->count() > 0)
        <div class="border-t border-neutral-200 pt-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-neutral-900">
                    Semua Ulasan ({{ $reviews->count() }})
                </h3>
                <select
                    wire:model.live="sortBy"
                    class="text-sm py-2 pr-8 border border-neutral-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cream-500"
                >
                    <option value="latest">Terbaru</option>
                    <option value="highest">Rating Tertinggi</option>
                    <option value="lowest">Rating Terendah</option>
                </select>
            </div>
            
            <div class="space-y-6">
                @foreach($reviews as $review)
                    <div class="border-b border-neutral-100 pb-6 last:border-0">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-cream-500 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-bold text-neutral-900">
                                        {{ substr($review->customer->name, 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="font-semibold text-neutral-900">{{ $review->customer->name }}</p>
                                    <p class="text-xs text-neutral-500">
                                        {{ $review->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <x-ui.star-rating :rating="$review->rating" />
                            </div>
                        </div>
                        
                        @if($review->review)
                            <p class="text-neutral-600 leading-relaxed">
                                {{ $review->review }}
                            </p>
                        @endif
                        
                        <!-- Admin Response -->
                        @if($review->admin_response)
                            <div class="mt-4 bg-neutral-50 border border-neutral-200 rounded-lg p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-4 h-4 text-cream-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    <span class="text-sm font-semibold text-neutral-900">Respons dari {{ $settings['app_name'] ?? 'Toko Roti' }}</span>
                                </div>
                                <p class="text-sm text-neutral-600">
                                    {{ $review->admin_response }}
                                </p>
                                @if($review->approved_at)
                                    <p class="text-xs text-neutral-500 mt-2">
                                        Dibalas {{ $review->approved_at->diffForHumans() }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="border-t border-neutral-200 pt-6 text-center py-8">
            <svg class="w-12 h-12 text-neutral-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            <p class="text-neutral-600">
                Belum ada ulasan untuk produk ini
            </p>
            <p class="text-sm text-neutral-500 mt-1">
                Jadilah yang pertama memberikan ulasan
            </p>
        </div>
    @endif
</div>
