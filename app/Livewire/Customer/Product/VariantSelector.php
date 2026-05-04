<?php

namespace App\Livewire\Customer\Product;

use App\Models\ProductVariant;
use Livewire\Component;

class VariantSelector extends Component
{
    public $productId;
    public $selectedVariantId = null;
    public $variants;

    public function mount($productId)
    {
        $this->productId = $productId;
        $this->variants = ProductVariant::where('product_id', $productId)
            ->active()
            ->inStock()
            ->get();
        
        if ($this->variants->isNotEmpty()) {
            $this->selectedVariantId = $this->variants->first()->id;
        }
    }

    public function selectVariant($variantId)
    {
        $this->selectedVariantId = $variantId;
        $variant = $this->variants->firstWhere('id', $variantId);
        
        $this->dispatch('variant-selected', [
            'variantId' => $variantId,
            'price' => $variant->final_price,
            'stock' => $variant->stock,
        ]);
    }

    public function render()
    {
        return view('livewire.customer.product.variant-selector');
    }
}
