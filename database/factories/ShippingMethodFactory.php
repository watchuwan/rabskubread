<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ShippingMethodFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'           => fake()->words(2, true),
            'code'           => strtoupper(fake()->bothify('???##')),
            'description'    => fake()->sentence(),
            'cost'           => fake()->randomElement([0, 10000, 25000, 35000]),
            'estimated_days' => fake()->numberBetween(0, 5),
            'is_active'      => true,
            'sort_order'     => 0,
        ];
    }

    public function pickup(): static
    {
        return $this->state(['code' => 'PICKUP', 'cost' => 0, 'estimated_days' => 0]);
    }
}
