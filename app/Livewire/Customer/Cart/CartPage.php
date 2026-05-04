<?php

namespace App\Livewire\Customer\Cart;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Voucher;
use App\Services\PromotionService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.customer')]
class CartPage extends Component
{
    public $cartItems = [];
    public $shippingMethods = [];
    public ?int $selectedShippingMethod = null;
    public float $subtotal = 0;
    public float $tax = 0;
    public float $shippingCost = 0;
    public float $total = 0;
    public float $discount = 0;
    public float $promotionDiscount = 0;
    public string $voucherCode = '';
    
    // For delete confirmation modal
    public ?int $itemToDelete = null;
    public ?string $productNameToDelete = null;
    
    // For clear cart modal
    public bool $showClearCartModal = false;

    protected $listeners = ['cart-updated' => 'loadCart'];

    public function mount(): void
    {
        $this->loadCart();
    }

    public function loadCart(): void
    {
        $customer = auth()->guard('customer')->user();
        $cart = Cart::with('items.product.category')->where('customer_id', $customer->id)->first();

        if ($cart) {
            $this->cartItems = $cart->items->load('product');
            $this->loadShippingMethods();
            $this->calculateTotals();
        } else {
            $this->cartItems = collect();
            $this->shippingMethods = collect();
            $this->resetTotals();
        }
    }

    public function loadShippingMethods(): void
    {
        $productIds = $this->cartItems->pluck('product_id')->toArray();
        
        if (empty($productIds)) {
            $this->shippingMethods = collect();
            return;
        }
        
        $this->shippingMethods = \App\Models\ShippingMethod::where('is_active', true)
            ->whereHas('products', function ($query) use ($productIds) {
                $query->whereIn('products.id', $productIds);
            })
            ->orderBy('sort_order', 'asc')
            ->get()
            ->filter(function ($method) use ($productIds) {
                return $method->products()->whereIn('products.id', $productIds)->count() === count($productIds);
            })
            ->values();
        
        // Fallback ke PICKUP jika tidak ada shipping method
        if ($this->shippingMethods->count() === 0) {
            $pickupMethod = \App\Models\ShippingMethod::where('code', 'PICKUP')
                ->where('is_active', true)
                ->first();
            
            if ($pickupMethod) {
                $this->shippingMethods = collect([$pickupMethod]);
            }
        }
        
        // Set default shipping method
        if ($this->shippingMethods->count() > 0 && !$this->selectedShippingMethod) {
            $this->selectedShippingMethod = $this->shippingMethods->first()->id;
            $this->shippingCost = $this->shippingMethods->first()->cost;
        }
    }

    public function updatedSelectedShippingMethod($value): void
    {
        $shippingMethod = \App\Models\ShippingMethod::find($value);
        if ($shippingMethod) {
            $this->shippingCost = $shippingMethod->cost;
            $this->calculateTotals();
        }
    }

    public function updateQuantity(int $cartItemId, int $quantity): void
    {
        if ($quantity < 1) {
            $this->removeItem($cartItemId);
            return;
        }

        $cartItem = CartItem::findOrFail($cartItemId);

        if ($quantity > $cartItem->product->stock) {
            $this->dispatch('toast', message: 'Jumlah melebihi stok tersedia', type: 'error');
            return;
        }

        $cartItem->update(['quantity' => $quantity]);
        $this->loadCart();

        $this->dispatch('cart-updated');
        $this->dispatch('toast', message: 'Keranjang diperbarui', type: 'success');
    }

    public function confirmDelete(int $cartItemId, string $productName): void
    {
        $this->itemToDelete = $cartItemId;
        $this->productNameToDelete = $productName;
        $this->dispatch('open-delete-modal');
    }

    public function removeItem(int $cartItemId = null): void
    {
        $cartItemId = $cartItemId ?? $this->itemToDelete;
        
        if (!$cartItemId) {
            return;
        }
        
        $cartItem = CartItem::findOrFail($cartItemId);
        $productName = $cartItem->product->name;
        $cartItem->delete();

        $this->loadCart();
        $this->dispatch('cart-updated');
        $this->dispatch('toast', message: "{$productName} dihapus dari keranjang", type: 'success');
        
        // Close modal
        $this->itemToDelete = null;
        $this->productNameToDelete = null;
        $this->dispatch('close-delete-modal');
    }

    public function confirmClearCart(): void
    {
        $this->showClearCartModal = true;
    }

    public function clearCart(): void
    {
        $customer = auth()->guard('customer')->user();
        $cart = Cart::where('customer_id', $customer->id)->first();

        if ($cart) {
            $itemCount = $cart->items()->count();
            $cart->items()->delete();
            $this->discount = 0;
            $this->voucherCode = '';
            $this->loadCart();
            $this->dispatch('cart-updated');
            $this->dispatch('toast', message: "{$itemCount} produk dihapus dari keranjang", type: 'success');
        }
        
        $this->showClearCartModal = false;
    }

    public function applyVoucher(): void
    {
        if (empty(trim($this->voucherCode))) {
            $this->dispatch('toast', message: 'Masukkan kode voucher', type: 'error');
            return;
        }

        $voucher = Voucher::where('code', strtoupper(trim($this->voucherCode)))->first();

        if (!$voucher || !$voucher->isValid()) {
            $this->dispatch('toast', message: 'Kode voucher tidak valid atau sudah kadaluarsa', type: 'error');
            return;
        }

        $discountAmount = $voucher->calculateDiscount($this->subtotal);

        if ($discountAmount <= 0) {
            $this->dispatch('toast', message: 'Subtotal tidak memenuhi minimum order voucher ini (min. Rp ' . number_format($voucher->min_order_amount, 0, ',', '.') . ')', type: 'error');
            return;
        }

        $this->discount = $discountAmount;
        $this->calculateTotals();

        $this->dispatch('toast', message: 'Voucher berhasil diterapkan! Diskon Rp ' . number_format($discountAmount, 0, ',', '.'), type: 'success');
    }

    public function calculateTotals(): void
    {
        $result = (new PromotionService())->applyToCartItems($this->cartItems);
        $this->cartItems        = $result['items'];
        $this->promotionDiscount = $result['promotion_discount'];

        $this->subtotal = $this->cartItems->sum(fn ($item) => $item->product->price * $item->quantity);
        $this->tax      = $this->subtotal * 0.11;
        $this->total    = $this->subtotal - $this->promotionDiscount + $this->tax + $this->shippingCost - $this->discount;
    }

    public function resetTotals(): void
    {
        $this->subtotal          = 0;
        $this->tax               = 0;
        $this->shippingCost      = 0;
        $this->total             = 0;
        $this->discount          = 0;
        $this->promotionDiscount = 0;
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.customer.cart.cart-page');
    }
}
