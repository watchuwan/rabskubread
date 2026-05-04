<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id'         => Category::factory(),
            'name'                => fake()->words(3, true),
            'description'         => fake()->paragraph(),
            'price'               => fake()->numberBetween(10000, 100000),
            'stock'               => fake()->numberBetween(10, 100),
            'low_stock_threshold' => 5,
            'sku'                 => 'SKU-' . strtoupper(fake()->bothify('????????')),
            'is_active'           => true,
            'rating_average'      => 0,
            'review_count'        => 0,
            'view_count'          => 0,
        ];
    }

    public function outOfStock(): static
    {
        return $this->state(['stock' => 0]);
    }
}
