<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoucherResourceTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
        $this->admin->assignRole('super_admin');
    }

    public function test_admin_can_access_vouchers_list(): void
    {
        $this->actingAs($this->admin)
            ->get('/admin/vouchers')
            ->assertStatus(200);
    }

    public function test_voucher_code_is_unique(): void
    {
        Voucher::factory()->create(['code' => 'UNIQUE10']);

        $this->assertDatabaseHas('vouchers', ['code' => 'UNIQUE10']);
        $this->assertEquals(1, Voucher::where('code', 'UNIQUE10')->count());
    }

    public function test_valid_scope_returns_active_non_expired(): void
    {
        Voucher::factory()->create(['is_active' => true, 'valid_until' => now()->addDay()]);
        Voucher::factory()->create(['is_active' => false]);
        Voucher::factory()->expired()->create();

        $this->assertEquals(1, Voucher::valid()->count());
    }
}
