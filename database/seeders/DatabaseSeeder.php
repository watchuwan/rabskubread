<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ShieldSeeder::class,
            CategorySeeder::class,
            ShippingMethodSeeder::class,
            VoucherSeeder::class,
            CustomerSeeder::class,
            ProductSeeder::class,
            PromotionSeeder::class,
            AddressSeeder::class,
            ProductReviewSeeder::class,
            OrderSeeder::class,
            SettingSeeder::class,
            // ThemeSeeder::class, // Apply a theme preset — change $active in ThemeSeeder to switch
            FaqSeeder::class,
            // ProductCsvSeeder::class, // Seed categories & products from database/csv/*.csv
        ]);

        $this->command->info('✅ Database seeded successfully!');
    }
}
