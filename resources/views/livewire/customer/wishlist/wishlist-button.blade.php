<button
    wire:click.stop="toggle"
    class="p-2 rounded-lg transition-colors {{ $isInWishlist ? 'bg-red-50 hover:bg-red-100' : 'bg-white hover:bg-neutral-50' }}"
    title="{{ $isInWishlist ? 'Hapus dari Wishlist' : 'Tambah ke Wishlist' }}"
>
    <svg class="w-5 h-5 {{ $isInWishlist ? 'text-red-500 fill-red-500' : 'text-neutral-600' }}" fill="{{ $isInWishlist ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
    </svg>
</button>
