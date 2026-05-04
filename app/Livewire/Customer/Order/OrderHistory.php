<?php

namespace App\Livewire\Customer\Order;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.customer')]
class OrderHistory extends Component
{
    use WithPagination;

    public string $statusFilter = '';

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function getOrdersProperty()
    {
        $customer = auth()->guard('customer')->user();
        
        $query = Order::query()
            ->where('customer_id', $customer->id)
            ->with(['items.product'])
            ->orderBy('created_at', 'desc');

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        return $query->paginate(10);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.customer.order.order-history', [
            'orders' => $this->orders,
        ]);
    }
}
