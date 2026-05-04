<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_total_calculation(): void
    {
        $order = Order::factory()->create([
            'subtotal'         => 100000,
            'shipping_cost'    => 15000,
            'voucher_discount' => 10000,
            'total_amount'     => 105000,
        ]);

        $this->assertEquals(105000, $order->total_amount);
    }

    public function test_order_can_be_cancelled(): void
    {
        $order = Order::factory()->create(['status' => 'pending']);
        $this->assertTrue($order->canBeCancelled());

        $order->update(['status' => 'cancelled', 'cancelled_at' => now()]);
        $this->assertEquals('cancelled', $order->fresh()->status);
    }

    public function test_completed_order_cannot_be_cancelled(): void
    {
        $order = Order::factory()->create(['status' => 'completed']);
        $this->assertFalse($order->canBeCancelled());
    }

    public function test_payment_success_updates_order(): void
    {
        $order   = Order::factory()->create(['status' => 'pending']);
        $payment = Payment::factory()->create(['order_id' => $order->id, 'status' => 'pending']);

        $payment->update(['status' => 'success', 'paid_at' => now()]);
        $order->update(['status' => 'processing', 'paid_at' => now()]);

        $this->assertEquals('success', $payment->fresh()->status);
        $this->assertEquals('processing', $order->fresh()->status);
        $this->assertNotNull($order->fresh()->paid_at);
    }

    public function test_admin_can_access_panel(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('super_admin');
        $this->assertTrue($admin->canAccessPanel(app('filament')->getPanel('admin')));
    }
}
