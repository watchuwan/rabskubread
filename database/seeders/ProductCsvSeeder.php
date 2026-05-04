<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductCsvSeeder extends Seeder
{
    private string $csvPath;

    public function __construct()
    {
        $this->csvPath = database_path('csv');
    }

    public function run(): void
    {
        $this->seedCategories();
        $this->seedProducts();
    }

    private function seedCategories(): void
    {
        $rows = $this->readCsv('categories.csv');

        foreach ($rows as $row) {
            Category::updateOrCreate(
                ['name' => $row['name']],
                [
                    'description' => $row['description'] ?: null,
                    'sort_order'  => (int) $row['sort_order'],
                    'is_active'   => (bool) $row['is_active'],
                ]
            );
        }
    }

    private function seedProducts(): void
    {
        $rows = $this->readCsv('products.csv');

        foreach ($rows as $row) {
            $category = Category::where('name', $row['category'])->first();

            if (! $category) {
                $this->command->warn("Category '{$row['category']}' not found, skipping {$row['name']}");
                continue;
            }

            Product::updateOrCreate(
                ['sku' => $row['sku']],
                [
                    'category_id'          => $category->id,
                    'name'                 => $row['name'],
                    'description'          => $row['description'] ?: null,
                    'price'                => (float) $row['price'],
                    'stock'                => (int) $row['stock'],
                    'low_stock_threshold'  => (int) ($row['low_stock_threshold'] ?: 10),
                    'ingredients'          => $row['ingredients'] ? array_map('trim', explode(',', $row['ingredients'])) : null,
                    'allergens'            => $row['allergens'] ? array_map('trim', explode(',', $row['allergens'])) : null,
                    'preparation_time'     => $row['preparation_time'] ? (int) $row['preparation_time'] : null,
                    'size'                 => $row['size'] ?: null,
                    'calories'             => $row['calories'] ? (int) $row['calories'] : null,
                    'is_customizable'      => (bool) $row['is_customizable'],
                    'is_active'            => (bool) $row['is_active'],
                ]
            );
        }
    }

    private function readCsv(string $filename): array
    {
        $path = $this->csvPath . '/' . $filename;

        if (! file_exists($path)) {
            $this->command->error("CSV file not found: {$path}");
            return [];
        }

        $handle = fopen($path, 'r');
        $headers = fgetcsv($handle);
        $rows = [];

        while (($data = fgetcsv($handle)) !== false) {
            if (count($data) === count($headers)) {
                $rows[] = array_combine($headers, $data);
            }
        }

        fclose($handle);
        return $rows;
    }
}
