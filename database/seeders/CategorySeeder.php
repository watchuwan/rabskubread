<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Roti Tawar', 'description' => 'Roti tawar segar untuk sarapan', 'sort_order' => 1],
            ['name' => 'Croissant',  'description' => 'Croissant renyah ala Prancis',   'sort_order' => 2],
            ['name' => 'Donat',      'description' => 'Donat lembut dengan berbagai topping', 'sort_order' => 3],
            ['name' => 'Kue Kering', 'description' => 'Cookies dan kue kering premium', 'sort_order' => 4],
            ['name' => 'Cake',       'description' => 'Cake untuk berbagai acara',       'sort_order' => 5],
            ['name' => 'Pastry',     'description' => 'Pastry dan pie lezat',            'sort_order' => 6],
            ['name' => 'Baguette',   'description' => 'Baguette khas Prancis',           'sort_order' => 7],
            ['name' => 'Cupcake',    'description' => 'Cupcake cantik dan lezat',        'sort_order' => 8],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['name' => $cat['name']],
                array_merge($cat, ['is_active' => true])
            );
        }
    }
}
