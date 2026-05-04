<?php

namespace App\Livewire\Customer\Comparison;

use App\Models\ProductComparison;
use Livewire\Component;

class ComparisonPage extends Component
{
    public function removeFromComparison($productId)
    {
        ProductComparison::toggle(auth()->id(), $productId);
        $this->dispatch('comparison-updated');
    }

    public function clearAll()
    {
        auth()->user()->comparisons()->delete();
        $this->dispatch('comparison-updated');
    }

    public function render()
    {
        $comparisons = ProductComparison::getForCustomer(auth()->id());

        return view('livewire.customer.comparison.comparison-page', [
            'comparisons' => $comparisons,
        ])->layout('layouts.customer');
    }
}
