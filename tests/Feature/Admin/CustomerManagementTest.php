<?php

namespace Tests\Feature\Admin;

use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_be_deactivated(): void
    {
        $customer = Customer::factory()->create(['is_active' => true]);
        $customer->update(['is_active' => false]);

        $this->assertDatabaseHas('customers', ['id' => $customer->id, 'is_active' => false]);
    }

    public function test_customer_with_orders(): void
    {
        $customer = Customer::factory()->create();
        Order::factory()->count(3)->create(['customer_id' => $customer->id]);

        $this->assertEquals(3, Order::where('customer_id', $customer->id)->count());
    }

    public function test_customer_total_spent_accessor(): void
    {
        $customer = Customer::factory()->create();
        Order::factory()->create(['customer_id' => $customer->id, 'status' => 'completed', 'total_amount' => 100000]);
        Order::factory()->create(['customer_id' => $customer->id, 'status' => 'completed', 'total_amount' => 50000]);
        Order::factory()->create(['customer_id' => $customer->id, 'status' => 'pending', 'total_amount' => 200000]);

        $this->assertEquals(150000, $customer->total_spent);
    }

    public function test_customer_total_orders_accessor(): void
    {
        $customer = Customer::factory()->create();
        Order::factory()->count(4)->create(['customer_id' => $customer->id]);

        $this->assertEquals(4, $customer->total_orders);
    }

    public function test_admin_staff_can_access_panel(): void
    {
        $staff = User::factory()->create();
        $staff->assignRole('admin_staff');

        $this->assertTrue($staff->canAccessPanel(app('filament')->getPanel('admin')));
    }
}
