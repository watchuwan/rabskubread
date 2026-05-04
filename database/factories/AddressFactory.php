<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'label'        => fake()->randomElement(['Rumah', 'Kantor', 'Lainnya']),
            'phone'        => fake()->phoneNumber(),
            'street_address' => fake()->streetAddress(),
            'city'         => fake()->city(),
            'state'        => fake()->state(),
            'postal_code'  => fake()->postcode(),
            'country'      => 'Indonesia',
            'is_default'   => false,
        ];
    }

    public function default(): static
    {
        return $this->state(['is_default' => true]);
    }
}
