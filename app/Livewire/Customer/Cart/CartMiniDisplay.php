<?php

namespace App\Livewire\Customer\Cart;

use App\Models\Cart;
use App\Models\CartItem;
use Livewire\Component;

class CartMiniDisplay extends Component
{
    public $cartItems = [];
    public $subtotal = 0;
    public $itemsCount = 0;
    public bool $isOpen = false;

    protected $listeners = ['cart-updated' => 'loadCart'];

    public function mount(): void
    {
        $this->loadCart();
    }

    public function loadCart(): void
    {
        if (auth()->guard('customer')->check()) {
            $customer = auth()->guard('customer')->user();
            $cart = Cart::with('items.product')->where('customer_id', $customer->id)->first();

            if ($cart) {
                $this->cartItems = $cart->items->take(3); // Show max 3 items
                $this->subtotal = $cart->subtotal;
                $this->itemsCount = $cart->items_count;
            } else {
                $this->cartItems = [];
                $this->subtotal = 0;
                $this->itemsCount = 0;
            }
        } else {
            $this->cartItems = [];
            $this->subtotal = 0;
            $this->itemsCount = 0;
        }
    }

    public function increaseQuantity(int $cartItemId): void
    {
        $cartItem = CartItem::findOrFail($cartItemId);
        
        // Check stock availability
        if ($cartItem->quantity >= $cartItem->product->stock) {
            $this->dispatch('toast', message: 'Jumlah melebihi stok tersedia', type: 'error');
            return;
        }

        $cartItem->increment('quantity');
        $this->loadCart();
        
        $this->dispatch('cart-updated');
        $this->dispatch('toast', message: 'Quantity diperbarui', type: 'success');
    }

    public function decreaseQuantity(int $cartItemId): void
    {
        $cartItem = CartItem::findOrFail($cartItemId);
        
        if ($cartItem->quantity <= 1) {
            // Remove item if quantity is 1
            $this->removeItem($cartItemId);
            return;
        }

        $cartItem->decrement('quantity');
        $this->loadCart();
        
        $this->dispatch('cart-updated');
        $this->dispatch('toast', message: 'Quantity diperbarui', type: 'success');
    }

    public function removeItem(int $cartItemId): void
    {
        $cartItem = CartItem::findOrFail($cartItemId);
        $cartItem->delete();
        
        $this->loadCart();
        $this->dispatch('cart-updated');
        $this->dispatch('toast', message: 'Item dihapus dari keranjang', type: 'success');
    }

    public function toggle(): void
    {
        $this->isOpen = !$this->isOpen;
    }

    public function render()
    {
        return view('livewire.customer.cart.cart-mini-display');
    }
}
