@php
    $categoryLabels = [
        'product'  => 'Produk',
        'delivery' => 'Pengiriman',
        'payment'  => 'Pembayaran',
        'other'    => 'Lainnya',
    ];
@endphp

<div x-data="{ activeCategory: 'all' }">
    <!-- Category Filter -->
    <div class="mb-8">
        <div class="flex flex-wrap gap-2 justify-center">
            <button @click="activeCategory = 'all'"
                    :class="activeCategory === 'all' ? 'bg-cream-400 text-neutral-900' : 'bg-white text-neutral-600 hover:bg-cream-100'"
                    class="px-4 py-2 rounded-lg font-medium text-sm transition-colors">
                Semua
            </button>
            @foreach($faqs->keys() as $cat)
                <button @click="activeCategory = '{{ $cat }}'"
                        :class="activeCategory === '{{ $cat }}' ? 'bg-cream-400 text-neutral-900' : 'bg-white text-neutral-600 hover:bg-cream-100'"
                        class="px-4 py-2 rounded-lg font-medium text-sm transition-colors">
                    {{ $categoryLabels[$cat] ?? ucfirst($cat) }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- FAQ Items -->
    <div class="space-y-4">
        @foreach($faqs as $category => $items)
            @foreach($items as $faq)
                <div x-show="activeCategory === 'all' || activeCategory === '{{ $category }}'"
                     x-transition
                     x-data="{ open: false }"
                     class="card overflow-hidden">
                    <button @click="open = !open" class="w-full p-6 text-left flex items-center justify-between gap-4">
                        <span class="font-semibold text-neutral-900">{{ $faq->question }}</span>
                        <svg class="w-5 h-5 text-neutral-400 flex-shrink-0 transition-transform duration-200"
                             :class="open ? 'rotate-180' : ''"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="px-6 pb-6 border-t border-neutral-100 pt-4">
                        <p class="text-neutral-600">{{ $faq->answer }}</p>
                    </div>
                </div>
            @endforeach
        @endforeach

        @if($faqs->isEmpty())
            <div class="card p-12 text-center text-neutral-400">
                <p>Belum ada FAQ tersedia.</p>
            </div>
        @endif
    </div>
</div>
