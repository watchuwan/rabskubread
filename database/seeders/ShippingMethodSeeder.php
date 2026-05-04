<?php

namespace Database\Seeders;

use App\Models\ShippingMethod;
use Illuminate\Database\Seeder;

class ShippingMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            ['name' => 'Ambil di Toko', 'code' => 'PICKUP', 'cost' => 0, 'estimated_days' => 0, 'sort_order' => 0, 'desc' => 'Ambil sendiri di toko (gratis)'],
            ['name' => 'Reguler', 'code' => 'REG', 'cost' => 10000, 'estimated_days' => 3, 'sort_order' => 1, 'desc' => 'Pengiriman reguler 3 hari'],
            ['name' => 'Express', 'code' => 'EXP', 'cost' => 25000, 'estimated_days' => 1, 'sort_order' => 2, 'desc' => 'Pengiriman express 1 hari'],
            ['name' => 'Same Day', 'code' => 'SAME', 'cost' => 35000, 'estimated_days' => 0, 'sort_order' => 3, 'desc' => 'Pengiriman di hari yang sama'],
        ];

        foreach ($methods as $method) {
            ShippingMethod::updateOrCreate(
                ['code' => $method['code']],
                [
                    'name' => $method['name'],
                    'cost' => $method['cost'],
                    'estimated_days' => $method['estimated_days'],
                    'description' => $method['desc'],
                    'sort_order' => $method['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
