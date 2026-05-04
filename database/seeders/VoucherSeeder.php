<?php

namespace Database\Seeders;

use App\Models\Voucher;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        $vouchers = [
            [
                'code' => 'WELCOME10',
                'name' => 'Welcome Discount 10%',
                'description' => 'Diskon 10% untuk member baru',
                'type' => 'percentage',
                'value' => 10,
                'min_order_amount' => 50000,
                'max_discount' => 20000,
                'usage_limit' => 100,
                'valid_from' => now(),
                'valid_until' => now()->addMonths(3),
            ],
            [
                'code' => 'FREESHIP',
                'name' => 'Free Shipping',
                'description' => 'Gratis ongkir untuk pembelian min 100rb',
                'type' => 'fixed',
                'value' => 10000,
                'min_order_amount' => 100000,
                'max_discount' => 10000,
                'usage_limit' => 50,
                'valid_from' => now(),
                'valid_until' => now()->addMonths(1),
            ],
            [
                'code' => 'DISC50K',
                'name' => 'Diskon 50rb',
                'description' => 'Potongan langsung 50rb untuk pembelian min 200rb',
                'type' => 'fixed',
                'value' => 50000,
                'min_order_amount' => 200000,
                'max_discount' => 50000,
                'usage_limit' => 30,
                'valid_from' => now(),
                'valid_until' => now()->addMonths(2),
            ],
        ];

        foreach ($vouchers as $voucher) {
            Voucher::updateOrCreate(
                ['code' => $voucher['code']],
                array_merge($voucher, ['is_active' => true, 'usage_count' => 0])
            );
        }
    }
}
