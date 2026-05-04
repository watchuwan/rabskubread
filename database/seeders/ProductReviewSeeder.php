<?php

namespace Database\Seeders;

use App\Models\{Customer, Product, ProductReview};
use Illuminate\Database\Seeder;

class ProductReviewSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $products = Product::all();

        $reviews = [
            ['rating' => 5, 'review' => 'Sangat enak dan fresh! Recommended banget.'],
            ['rating' => 5, 'review' => 'Rotinya lembut, rasanya pas. Pasti beli lagi.'],
            ['rating' => 4, 'review' => 'Enak, tapi harganya agak mahal. Overall oke.'],
            ['rating' => 5, 'review' => 'Terbaik! Selalu fresh dan packaging rapi.'],
            ['rating' => 4, 'review' => 'Rasanya enak, cuma kadang stoknya habis.'],
            ['rating' => 5, 'review' => 'Favorit keluarga! Anak-anak suka banget.'],
        ];

        foreach ($products as $product) {
            // Random 2-4 reviews per product
            $reviewCount = rand(2, 4);
            $selectedCustomers = $customers->random(min($reviewCount, $customers->count()));

            foreach ($selectedCustomers as $customer) {
                $review = $reviews[array_rand($reviews)];
                
                ProductReview::updateOrCreate(
                    ['product_id' => $product->id, 'customer_id' => $customer->id],
                    [
                        'rating' => $review['rating'],
                        'review' => $review['review'],
                        'is_approved' => true,
                    ]
                );
            }

            // Update product rating
            $product->updateRating();
        }
    }
}
