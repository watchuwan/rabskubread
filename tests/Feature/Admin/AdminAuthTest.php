<?php

namespace Tests\Feature\Admin;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_access_panel(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('super_admin');

        $this->assertTrue($admin->canAccessPanel(app('filament')->getPanel('admin')));
    }

    public function test_admin_staff_can_access_panel(): void
    {
        $staff = User::factory()->create();
        $staff->assignRole('admin_staff');

        $this->assertTrue($staff->canAccessPanel(app('filament')->getPanel('admin')));
    }

    public function test_inactive_user_cannot_access_panel(): void
    {
        $admin = User::factory()->create(['is_active' => false]);
        $admin->assignRole('super_admin');

        $this->assertFalse($admin->canAccessPanel(app('filament')->getPanel('admin')));
    }

    public function test_customer_cannot_access_admin_panel(): void
    {
        $customer = Customer::factory()->create();

        // Customer model doesn't implement FilamentUser for admin panel
        $this->assertFalse($customer->canAccessPanel(app('filament')->getPanel('admin')));
    }

    public function test_user_without_role_cannot_access_panel(): void
    {
        $user = User::factory()->create();

        $this->assertFalse($user->canAccessPanel(app('filament')->getPanel('admin')));
    }
}
