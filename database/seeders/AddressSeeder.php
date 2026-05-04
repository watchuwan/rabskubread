<?php

namespace Database\Seeders;

use App\Models\{Address, Customer};
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();

        foreach ($customers as $index => $customer) {
            Address::updateOrCreate(
                ['customer_id' => $customer->id, 'label' => 'Rumah'],
                [
                    'phone' => $customer->phone,
                    'street_address' => 'Jl. Raya Contoh No. ' . ($index + 10),
                    'city' => 'Jakarta Selatan',
                    'state' => 'DKI Jakarta',
                    'postal_code' => '12' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                    'country' => 'Indonesia',
                    'is_default' => true,
                ]
            );

            // Tambah alamat kantor untuk beberapa customer
            if ($index % 2 === 0) {
                Address::updateOrCreate(
                    ['customer_id' => $customer->id, 'label' => 'Kantor'],
                    [
                        'phone' => $customer->phone,
                        'street_address' => 'Jl. Sudirman No. ' . ($index + 100),
                        'city' => 'Jakarta Pusat',
                        'state' => 'DKI Jakarta',
                        'postal_code' => '10' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                        'country' => 'Indonesia',
                        'is_default' => false,
                    ]
                );
            }
        }
    }
}
