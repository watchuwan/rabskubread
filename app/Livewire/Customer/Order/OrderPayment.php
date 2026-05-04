<?php

namespace App\Livewire\Customer\Order;

use App\Models\Order;
use App\Models\Payment;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.customer')]
class OrderPayment extends Component
{
    public Order $order;
    public ?string $snapToken = null;
    public string $snapUrl = '';

    public function mount(Order $order): void
    {
        $customer = auth()->guard('customer')->user();

        // Verify order belongs to customer
        if ($order->customer_id !== $customer->id) {
            abort(403);
        }

        $this->order = $order->load(['items.product', 'address', 'shippingMethod']);

        if ($this->order->status === 'completed') {
            $this->redirect(route('orders.show', $order->id), navigate: true);
            return;
        }

        $payment = Payment::where('order_id', $this->order->id)
            ->latest()
            ->first();

        $this->snapToken = $payment?->snap_token;
        $this->snapUrl   = config('services.midtrans.snap_url');
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.customer.order.payment');
    }
}
