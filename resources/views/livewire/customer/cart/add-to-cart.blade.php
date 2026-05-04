<div>
    @if($product && $product->in_stock)
        <button
            wire:click="addToCart"
            wire:loading.attr="disabled"
            class="p-2 bg-amber-500 rounded-lg hover:bg-amber-600 transition-colors disabled:opacity-50"
            title="Tambah ke Keranjang"
        >
            <span wire:loading.remove wire:target="addToCart">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </span>
            <span wire:loading wire:target="addToCart">
                <svg class="w-5 h-5 text-white animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </span>
        </button>
    @else
        <span class="text-xs text-neutral-400">Tidak tersedia</span>
    @endif
</div>
