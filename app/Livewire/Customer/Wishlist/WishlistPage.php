<?php

namespace App\Livewire\Customer\Wishlist;

use App\Models\Wishlist;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.customer')]
class WishlistPage extends Component
{
    public $wishlistItems = [];

    public function mount(): void
    {
        $this->loadWishlist();
    }

    public function loadWishlist(): void
    {
        $customer = auth()->guard('customer')->user();
        $this->wishlistItems = Wishlist::with(['product.category'])
            ->where('customer_id', $customer->id)
            ->get();
    }

    public function removeItem(int $wishlistId): void
    {
        $wishlist = Wishlist::findOrFail($wishlistId);
        $wishlist->delete();
        
        $this->loadWishlist();
        $this->dispatch('wishlist-updated');
        $this->dispatch('toast', message: 'Dihapus dari wishlist', type: 'info');
    }

    public function moveToCart(int $wishlistId): void
    {
        $wishlist = Wishlist::with('product')->findOrFail($wishlistId);
        
        if (!$wishlist->product->in_stock) {
            $this->dispatch('toast', message: 'Produk sedang habis', type: 'error');
            return;
        }

        $customer = auth()->guard('customer')->user();
        $cart = \App\Models\Cart::getOrCreateForCustomer($customer->id);

        // Check if item already in cart, increment quantity instead of creating new
        $cartItem = \App\Models\CartItem::where('cart_id', $cart->id)
            ->where('product_id', $wishlist->product->id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
            $message = 'Jumlah produk di keranjang ditambah';
        } else {
            \App\Models\CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $wishlist->product->id,
                'quantity' => 1,
                'price' => $wishlist->product->price,
            ]);
            $message = 'Ditambahkan ke keranjang';
        }

        $wishlist->delete();
        
        $this->loadWishlist();
        $this->dispatch('cart-updated');
        $this->dispatch('wishlist-updated');
        $this->dispatch('toast', message: $message, type: 'success');
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.customer.wishlist.wishlist-page');
    }
}
