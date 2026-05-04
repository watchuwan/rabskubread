<div>
    @if($variants->isNotEmpty())
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Select Variant</label>
            <div class="grid grid-cols-2 gap-2">
                @foreach($variants as $variant)
                    <button
                        wire:click="selectVariant({{ $variant->id }})"
                        class="px-4 py-3 border rounded-lg text-sm transition
                            {{ $selectedVariantId == $variant->id 
                                ? 'border-orange-500 bg-orange-50 text-orange-700 font-semibold' 
                                : 'border-gray-300 hover:border-orange-300' }}
                            {{ $variant->stock <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                        {{ $variant->stock <= 0 ? 'disabled' : '' }}
                    >
                        <div class="font-medium">{{ $variant->name }}</div>
                        <div class="text-xs mt-1">
                            @if($variant->price_adjustment != 0)
                                <span class="text-orange-600">
                                    {{ $variant->price_adjustment > 0 ? '+' : '' }}Rp {{ number_format(abs($variant->price_adjustment), 0, ',', '.') }}
                                </span>
                            @endif
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            Stock: {{ $variant->stock }}
                        </div>
                    </button>
                @endforeach
            </div>
        </div>
    @endif
</div>
