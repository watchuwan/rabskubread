<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VoucherFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code'             => strtoupper(fake()->bothify('????####')),
            'name'             => fake()->words(3, true),
            'description'      => fake()->sentence(),
            'type'             => fake()->randomElement(['percentage', 'fixed']),
            'value'            => fake()->numberBetween(5, 50),
            'min_order_amount' => 50000,
            'max_discount'     => 100000,
            'usage_limit'      => 100,
            'usage_count'      => 0,
            'valid_from'       => now()->subDay(),
            'valid_until'      => now()->addDays(30),
            'is_active'        => true,
        ];
    }

    public function percentage(int $value = 10): static
    {
        return $this->state(['type' => 'percentage', 'value' => $value]);
    }

    public function fixed(int $value = 10000): static
    {
        return $this->state(['type' => 'fixed', 'value' => $value]);
    }

    public function expired(): static
    {
        return $this->state(['valid_until' => now()->subDay()]);
    }
}
