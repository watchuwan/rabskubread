<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_soft_delete(): void
    {
        $customer = Customer::factory()->create();
        $customer->delete();

        $this->assertSoftDeleted('customers', ['id' => $customer->id]);
    }

    public function test_customer_referral_code_unique(): void
    {
        $c1 = Customer::factory()->create();
        $c2 = Customer::factory()->create();

        $this->assertNotEquals($c1->referral_code, $c2->referral_code);
    }

    public function test_customer_last_login_updated(): void
    {
        $customer = Customer::factory()->create(['last_login_at' => null]);
        $customer->updateLastLogin();

        $this->assertNotNull($customer->fresh()->last_login_at);
    }

    public function test_admin_can_access_panel(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('super_admin');
        $this->assertTrue($admin->canAccessPanel(app('filament')->getPanel('admin')));
    }
}
