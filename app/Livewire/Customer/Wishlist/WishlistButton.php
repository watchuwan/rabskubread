<?php

namespace App\Livewire\Customer\Wishlist;

use App\Models\Wishlist;
use Livewire\Component;

class WishlistButton extends Component
{
    public int $productId;
    public bool $isInWishlist = false;

    public function mount(): void
    {
        if (auth()->guard('customer')->check()) {
            $this->isInWishlist = Wishlist::where('customer_id', auth()->guard('customer')->id())
                ->where('product_id', $this->productId)
                ->exists();
        }
    }

    public function toggle(): void
    {
        if (!auth()->guard('customer')->check()) {
            $this->redirect(route('login'));
            return;
        }

        $customerId = auth()->guard('customer')->id();

        if ($this->isInWishlist) {
            Wishlist::where('customer_id', $customerId)
                ->where('product_id', $this->productId)
                ->delete();
            $this->isInWishlist = false;
            $this->dispatch('toast', message: 'Dihapus dari wishlist', type: 'info');
        } else {
            Wishlist::create(['customer_id' => $customerId, 'product_id' => $this->productId]);
            $this->isInWishlist = true;
            $this->dispatch('toast', message: 'Ditambahkan ke wishlist', type: 'success');
        }

        $this->dispatch('wishlist-updated');
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.customer.wishlist.wishlist-button');
    }
}
