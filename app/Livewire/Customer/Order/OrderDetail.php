<?php

namespace App\Livewire\Customer\Order;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.customer')]
class OrderDetail extends Component
{
    public Order $order;

    public function mount(Order $order): void
    {
        $customer = auth()->guard('customer')->user();

        // Verify order belongs to customer
        if ($order->customer_id !== $customer->id) {
            abort(403);
        }

        $this->order = $order->load(['items.product', 'address', 'shippingMethod', 'payment']);
    }

    public function cancelOrder(): void
    {
        if (!$this->order->canBeCancelled()) {
            $this->dispatch('toast', message: 'Pesanan tidak dapat dibatalkan', type: 'error');
            return;
        }

        $this->order->update([
            'status' => Order::STATUS_CANCELLED,
            'cancelled_at' => now(),
        ]);

        // Restore stock
        foreach ($this->order->items as $item) {
            $item->product->incrementStock($item->quantity);
        }

        $this->dispatch('toast', message: 'Pesanan berhasil dibatalkan', type: 'success');
    }

    public function reorder(): void
    {
        $customer = auth()->guard('customer')->user();
        $cart = \App\Models\Cart::getOrCreateForCustomer($customer->id);

        foreach ($this->order->items as $item) {
            $existing = \App\Models\CartItem::where('cart_id', $cart->id)
                ->where('product_id', $item->product_id)
                ->first();

            if ($existing) {
                $existing->increment('quantity', $item->quantity);
            } else {
                \App\Models\CartItem::create([
                    'cart_id'    => $cart->id,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                ]);
            }
        }

        $this->dispatch('cart-updated');
        $this->dispatch('toast', message: 'Produk ditambahkan ke keranjang', type: 'success');
    }

    public function downloadInvoice(): void
    {
        $this->redirect(route('orders.invoice', $this->order->id));
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.customer.order.order-detail', [
            'order' => $this->order,
        ]);
    }
}
