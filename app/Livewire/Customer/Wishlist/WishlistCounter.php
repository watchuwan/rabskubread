<?php

namespace App\Livewire\Customer\Wishlist;

use App\Models\Wishlist;
use Livewire\Attributes\On;
use Livewire\Component;

class WishlistCounter extends Component
{
    public int $count = 0;

    public function mount(): void
    {
        $this->updateCount();
    }

    #[On('wishlist-updated')]
    public function updateCount(): void
    {
        if (auth()->guard('customer')->check()) {
            $this->count = Wishlist::where('customer_id', auth()->guard('customer')->id())->count();
        } else {
            $this->count = 0;
        }
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.customer.wishlist.wishlist-counter');
    }
}
