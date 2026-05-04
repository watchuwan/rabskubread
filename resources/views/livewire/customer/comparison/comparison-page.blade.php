<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Product Comparison</h1>
        @if($comparisons->count() > 0)
            <button wire:click="clearAll" class="text-red-600 hover:text-red-800">
                Clear All
            </button>
        @endif
    </div>

    @if($comparisons->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 border">Feature</th>
                        @foreach($comparisons as $comparison)
                            <th class="px-4 py-2 border">
                                <img src="{{ $comparison->product->main_image }}" alt="{{ $comparison->product->name }}" class="w-20 h-20 object-cover mx-auto mb-2">
                                <div class="font-semibold">{{ $comparison->product->name }}</div>
                                <button wire:click="removeFromComparison({{ $comparison->product_id }})" class="text-red-600 text-sm mt-2">Remove</button>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-4 py-2 border font-semibold">Price</td>
                        @foreach($comparisons as $comparison)
                            <td class="px-4 py-2 border text-center">Rp {{ number_format($comparison->product->price, 0, ',', '.') }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="px-4 py-2 border font-semibold">Category</td>
                        @foreach($comparisons as $comparison)
                            <td class="px-4 py-2 border text-center">{{ $comparison->product->category->name }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="px-4 py-2 border font-semibold">Stock</td>
                        @foreach($comparisons as $comparison)
                            <td class="px-4 py-2 border text-center">{{ $comparison->product->stock }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="px-4 py-2 border font-semibold">Rating</td>
                        @foreach($comparisons as $comparison)
                            <td class="px-4 py-2 border text-center">⭐ {{ number_format($comparison->product->rating_average, 1) }}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-gray-500 mb-4">No products to compare</p>
            <a href="{{ route('products.index') }}" class="text-blue-600 hover:underline">Browse Products</a>
        </div>
    @endif
</div>
