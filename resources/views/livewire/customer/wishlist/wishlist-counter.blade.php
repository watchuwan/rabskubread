<a href="{{ route('wishlist.index') }}" wire:navigate class="relative p-2 rounded-lg hover:bg-amber-50 text-neutral-600 hover:text-amber-600 transition-colors">
    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
    </svg>
    @if($count > 0)
        <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">
            {{ $count > 9 ? '9+' : $count }}
        </span>
    @endif
</a>
