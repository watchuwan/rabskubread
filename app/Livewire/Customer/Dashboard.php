<?php

namespace App\Livewire\Customer;

use App\Models\Order;
use App\Models\Wishlist;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.customer')]
class Dashboard extends Component
{
    public function render(): \Illuminate\View\View
    {
        $customer = auth()->guard('customer')->user();
        
        $stats = [
            'total_orders' => Order::where('customer_id', $customer->id)->count(),
            'pending_orders' => Order::where('customer_id', $customer->id)
                ->where('status', 'pending')
                ->count(),
            'processing_orders' => Order::where('customer_id', $customer->id)
                ->whereIn('status', ['processing', 'shipped'])
                ->count(),
            'completed_orders' => Order::where('customer_id', $customer->id)
                ->where('status', 'completed')
                ->count(),
            'cancelled_orders' => Order::where('customer_id', $customer->id)
                ->where('status', 'cancelled')
                ->count(),
            'wishlist_count' => Wishlist::where('customer_id', $customer->id)->count(),
            'total_spent' => Order::where('customer_id', $customer->id)
                ->where('status', 'completed')
                ->sum('total_amount'),
        ];
        
        // Recent orders by status
        $pendingOrders = Order::where('customer_id', $customer->id)
            ->where('status', 'pending')
            ->with(['items.product', 'shippingMethod'])
            ->latest()
            ->limit(3)
            ->get();
            
        $processingOrders = Order::where('customer_id', $customer->id)
            ->whereIn('status', ['processing', 'shipped'])
            ->with(['items.product', 'shippingMethod'])
            ->latest()
            ->limit(3)
            ->get();
            
        $completedOrders = Order::where('customer_id', $customer->id)
            ->where('status', 'completed')
            ->with(['items.product', 'shippingMethod'])
            ->latest()
            ->limit(3)
            ->get();
        
        return view('livewire.customer.dashboard', compact(
            'stats', 
            'pendingOrders', 
            'processingOrders', 
            'completedOrders'
        ));
    }
}
