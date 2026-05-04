<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PromotionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'         => fake()->words(3, true),
            'description'  => fake()->sentence(),
            'type'         => fake()->randomElement(['bundle', 'package']),
            'price'        => fake()->numberBetween(20000, 200000),
            'min_quantity' => fake()->numberBetween(2, 10),
            'valid_from'   => now()->subDay(),
            'valid_until'  => now()->addMonth(),
            'is_active'    => true,
        ];
    }

    public function bundle(): static
    {
        return $this->state(['type' => 'bundle']);
    }

    public function package(): static
    {
        return $this->state(['type' => 'package']);
    }

    public function expired(): static
    {
        return $this->state(['valid_until' => now()->subDay()]);
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }
}
