<?php

namespace App\Livewire\Customer\Cart;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Livewire\Component;

class AddToCart extends Component
{
    public int $productId;
    public ?Product $product = null;
    public int $quantity = 1;

    protected $listeners = ['refreshComponent'];

    public function mount(int $productId): void
    {
        $this->productId = $productId;
        $this->product = Product::find($productId);
    }

    public function addToCart(): void
    {
        if (!auth()->guard('customer')->check()) {
            $this->redirect(route('login'), navigate: true);
            return;
        }

        if (!$this->product || !$this->product->in_stock) {
            $this->dispatch('toast', message: 'Produk tidak tersedia', type: 'error');
            return;
        }

        $customer = auth()->guard('customer')->user();
        $cart = Cart::getOrCreateForCustomer($customer->id);

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $this->product->id)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $this->quantity;
            
            if ($newQuantity > $this->product->stock) {
                $this->dispatch('toast', message: 'Jumlah melebihi stok tersedia', type: 'error');
                return;
            }
            
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            if ($this->quantity > $this->product->stock) {
                $this->dispatch('toast', message: 'Jumlah melebihi stok tersedia', type: 'error');
                return;
            }
            
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $this->product->id,
                'quantity' => $this->quantity,
            ]);
        }
        
        $this->dispatch('cart-updated');
        $this->dispatch('toast', message: 'Berhasil ditambahkan ke keranjang', type: 'success');
    }

    public function render()
    {
        return view('livewire.customer.cart.add-to-cart');
    }
}
