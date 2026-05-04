<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\PromotionItem;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        // Bundle: beli 10 Donat Coklat = Rp 100.000 (hemat Rp 20.000)
        $donat = Product::where('name', 'Donat Coklat')->first()
            ?? Product::whereHas('category', fn ($q) => $q->where('name', 'Donat'))->first();

        if ($donat) {
            $bundle = Promotion::updateOrCreate(
                ['slug' => 'bundle-donat-coklat-10pcs'],
                [
                    'name'         => 'Bundle Donat Coklat 10 Pcs',
                    'description'  => 'Beli 10 Donat Coklat hanya Rp 100.000, hemat Rp 20.000!',
                    'type'         => 'bundle',
                    'price'        => 100000,
                    'min_quantity' => 10,
                    'valid_from'   => now(),
                    'valid_until'  => now()->addMonths(3),
                    'is_active'    => true,
                ]
            );

            PromotionItem::updateOrCreate(
                ['promotion_id' => $bundle->id, 'product_id' => $donat->id],
                ['quantity' => 10]
            );
        }

        // Package: Meeting Package — Croissant Butter ×10 + Cookies Almond ×20 + Baguette Original ×2
        $croissant = Product::where('name', 'Croissant Butter')->first();
        $cookies   = Product::where('name', 'Cookies Almond')->first();
        $baguette  = Product::where('name', 'Baguette Original')->first();

        if ($croissant && $cookies && $baguette) {
            $package = Promotion::updateOrCreate(
                ['slug' => 'meeting-package'],
                [
                    'name'         => 'Meeting Package',
                    'description'  => 'Paket lengkap untuk rapat: Croissant, Cookies, dan Baguette dengan harga spesial.',
                    'type'         => 'package',
                    'price'        => 450000,
                    'min_quantity' => 1,
                    'valid_from'   => now(),
                    'valid_until'  => now()->addMonths(6),
                    'is_active'    => true,
                ]
            );

            foreach ([
                [$croissant->id, 10],
                [$cookies->id,   20],
                [$baguette->id,   2],
            ] as [$productId, $qty]) {
                PromotionItem::updateOrCreate(
                    ['promotion_id' => $package->id, 'product_id' => $productId],
                    ['quantity' => $qty]
                );
            }
        }

        // Bundle: beli 5 Cupcake Vanilla = Rp 60.000
        $cupcake = Product::where('name', 'Cupcake Vanilla')->first();

        if ($cupcake) {
            $bundleCupcake = Promotion::updateOrCreate(
                ['slug' => 'bundle-cupcake-vanilla-5pcs'],
                [
                    'name'         => 'Bundle Cupcake Vanilla 5 Pcs',
                    'description'  => 'Beli 5 Cupcake Vanilla hanya Rp 60.000!',
                    'type'         => 'bundle',
                    'price'        => 60000,
                    'min_quantity' => 5,
                    'valid_from'   => now(),
                    'valid_until'  => now()->addMonths(3),
                    'is_active'    => true,
                ]
            );

            PromotionItem::updateOrCreate(
                ['promotion_id' => $bundleCupcake->id, 'product_id' => $cupcake->id],
                ['quantity' => 5]
            );
        }
    }
}
