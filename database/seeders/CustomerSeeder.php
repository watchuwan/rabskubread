<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {

        $customers = [
            ['name' => 'Budi Santoso', 'email' => 'budi@example.com', 'phone' => '081234567890'],
            ['name' => 'Siti Nurhaliza', 'email' => 'siti@example.com', 'phone' => '081234567891'],
            ['name' => 'Ahmad Rizki', 'email' => 'ahmad@example.com', 'phone' => '081234567892'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi@example.com', 'phone' => '081234567893'],
            ['name' => 'Eko Prasetyo', 'email' => 'eko@example.com', 'phone' => '081234567894'],
        ];

        foreach ($customers as $customer) {
            Customer::updateOrCreate(
                ['email' => $customer['email']],
                array_merge($customer, [
                    'password' => Hash::make('password'),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ])
            );
        }
    }
}
