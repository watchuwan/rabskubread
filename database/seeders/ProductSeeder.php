<?php

namespace Database\Seeders;

use App\Models\{Category, Product};
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Roti Tawar Gandum', 'category' => 'Roti Tawar', 'price' => 15000, 'stock' => 50, 'desc' => 'Roti tawar gandum utuh, kaya serat'],
            ['name' => 'Roti Tawar Susu', 'category' => 'Roti Tawar', 'price' => 16000, 'stock' => 45, 'desc' => 'Roti tawar lembut dengan susu segar'],
            ['name' => 'Croissant Butter', 'category' => 'Croissant', 'price' => 18000, 'stock' => 30, 'desc' => 'Croissant klasik dengan butter premium'],
            ['name' => 'Croissant Almond', 'category' => 'Croissant', 'price' => 22000, 'stock' => 20, 'desc' => 'Croissant dengan filling almond'],
            ['name' => 'Donat Coklat', 'category' => 'Donat', 'price' => 12000, 'stock' => 40, 'desc' => 'Donat lembut dengan topping coklat'],
            ['name' => 'Donat Strawberry', 'category' => 'Donat', 'price' => 13000, 'stock' => 38, 'desc' => 'Donat dengan glaze strawberry'],
            ['name' => 'Cookies Almond', 'category' => 'Kue Kering', 'price' => 25000, 'stock' => 25, 'desc' => 'Cookies renyah dengan almond slice'],
            ['name' => 'Cookies Chocolate Chip', 'category' => 'Kue Kering', 'price' => 28000, 'stock' => 30, 'desc' => 'Cookies dengan chocolate chip premium'],
            ['name' => 'Red Velvet Cake', 'category' => 'Cake', 'price' => 85000, 'stock' => 10, 'desc' => 'Red velvet cake dengan cream cheese frosting'],
            ['name' => 'Chocolate Cake', 'category' => 'Cake', 'price' => 95000, 'stock' => 8, 'desc' => 'Chocolate cake berlapis dengan ganache'],
            ['name' => 'Baguette Original', 'category' => 'Baguette', 'price' => 20000, 'stock' => 15, 'desc' => 'Baguette khas Prancis dengan crust renyah'],
            ['name' => 'Cupcake Vanilla', 'category' => 'Cupcake', 'price' => 15000, 'stock' => 35, 'desc' => 'Cupcake vanilla dengan buttercream'],
        ];

        foreach ($products as $p) {
            $category = Category::where('name', $p['category'])->first();
            if (!$category) continue;

            Product::updateOrCreate(
                ['name' => $p['name']],
                [
                    'category_id' => $category->id,
                    'price' => $p['price'],
                    'stock' => $p['stock'],
                    'description' => $p['desc'],
                    'sku' => 'SKU-' . strtoupper(substr(md5($p['name']), 0, 8)),
                    'low_stock_threshold' => 10,
                    'is_active' => true,
                    'view_count' => rand(10, 500),
                    'rating_average' => rand(40, 50) / 10,
                    'review_count' => rand(5, 50),
                ]
            );
        }
    }
}
