<?php

namespace Tests\Feature\Customer;

use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoucherSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_percentage_voucher_calculates_discount(): void
    {
        $voucher = Voucher::factory()->percentage(10)->create(['min_order_amount' => 0]);

        $discount = $voucher->calculateDiscount(100000);
        $this->assertEquals(10000, $discount);
    }

    public function test_fixed_voucher_calculates_discount(): void
    {
        $voucher = Voucher::factory()->fixed(25000)->create(['min_order_amount' => 0]);

        $discount = $voucher->calculateDiscount(100000);
        $this->assertEquals(25000, $discount);
    }

    public function test_percentage_voucher_capped_by_max_discount(): void
    {
        $voucher = Voucher::factory()->percentage(50)->create([
            'min_order_amount' => 0,
            'max_discount'     => 20000,
        ]);

        $discount = $voucher->calculateDiscount(100000); // 50% = 50000, capped at 20000
        $this->assertEquals(20000, $discount);
    }

    public function test_discount_cannot_exceed_subtotal(): void
    {
        $voucher = Voucher::factory()->fixed(200000)->create(['min_order_amount' => 0]);

        $discount = $voucher->calculateDiscount(50000);
        $this->assertEquals(50000, $discount); // capped at subtotal
    }

    public function test_voucher_invalid_if_below_min_order(): void
    {
        $voucher = Voucher::factory()->create(['min_order_amount' => 100000]);

        $discount = $voucher->calculateDiscount(50000);
        $this->assertEquals(0, $discount);
    }

    public function test_expired_voucher_returns_zero_discount(): void
    {
        $voucher = Voucher::factory()->expired()->create();

        $discount = $voucher->calculateDiscount(100000);
        $this->assertEquals(0, $discount);
    }

    public function test_inactive_voucher_returns_zero_discount(): void
    {
        $voucher = Voucher::factory()->create(['is_active' => false]);

        $discount = $voucher->calculateDiscount(100000);
        $this->assertEquals(0, $discount);
    }

    public function test_voucher_at_usage_limit_is_invalid(): void
    {
        $voucher = Voucher::factory()->create(['usage_limit' => 5, 'usage_count' => 5]);

        $this->assertFalse($voucher->isValid());
    }

    public function test_increment_usage_count(): void
    {
        $voucher = Voucher::factory()->create(['usage_count' => 0]);
        $voucher->incrementUsage();

        $this->assertEquals(1, $voucher->fresh()->usage_count);
    }

    public function test_valid_scope_excludes_expired(): void
    {
        Voucher::factory()->create(['valid_until' => now()->addDay()]);
        Voucher::factory()->expired()->create();

        $this->assertEquals(1, Voucher::valid()->count());
    }
}
