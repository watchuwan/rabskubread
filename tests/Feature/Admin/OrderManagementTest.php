<?php

namespace Tests\Feature\Admin;

use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_status_change_recorded_in_history(): void
    {
        $order = Order::factory()->create(['status' => 'pending']);
        $order->update(['status' => 'processing']);

        $this->assertDatabaseHas('order_status_histories', [
            'order_id'    => $order->id,
            'from_status' => 'pending',
            'to_status'   => 'processing',
        ]);
    }

    public function test_order_status_history_has_multiple_entries(): void
    {
        $order = Order::factory()->create(['status' => 'pending']);
        $order->update(['status' => 'processing']);
        $order->update(['status' => 'shipped']);

        $this->assertEquals(2, $order->statusHistories()->count());
    }

    public function test_order_can_be_cancelled_by_admin(): void
    {
        $order = Order::factory()->create(['status' => 'processing']);
        $order->update(['status' => 'cancelled', 'cancelled_at' => now()]);

        $this->assertEquals('cancelled', $order->fresh()->status);
        $this->assertNotNull($order->fresh()->cancelled_at);
    }

    public function test_order_can_be_refunded(): void
    {
        $order = Order::factory()->create(['status' => 'completed']);
        $order->update(['status' => 'refunded']);

        $this->assertEquals('refunded', $order->fresh()->status);
    }

    public function test_super_admin_has_correct_role(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('super_admin');

        $this->assertTrue($admin->hasRole('super_admin'));
        $this->assertTrue($admin->canAccessPanel(app('filament')->getPanel('admin')));
    }

    public function test_non_admin_user_cannot_access_panel(): void
    {
        $customer = Customer::factory()->create();
        $this->assertFalse($customer->canAccessPanel(app('filament')->getPanel('admin')));
    }
}
