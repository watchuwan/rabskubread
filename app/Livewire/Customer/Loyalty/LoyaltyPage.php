<?php

namespace App\Livewire\Customer\Loyalty;

use App\Models\LoyaltyPoint;
use Livewire\Component;
use Livewire\WithPagination;

class LoyaltyPage extends Component
{
    use WithPagination;

    public function render()
    {
        $customer = auth()->user();
        $balance = $customer->loyalty_balance;
        
        $transactions = $customer->loyaltyPoints()
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('livewire.customer.loyalty.loyalty-page', [
            'balance' => $balance,
            'transactions' => $transactions,
        ])->layout('layouts.customer');
    }
}
