<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewRatingTest extends TestCase
{
    use RefreshDatabase;

    public function test_approved_review_updates_product_rating(): void
    {
        $product  = Product::factory()->create(['rating_average' => 0, 'review_count' => 0]);
        $customer = Customer::factory()->create();

        ProductReview::create([
            'product_id'  => $product->id,
            'customer_id' => $customer->id,
            'rating'      => 5,
            'review'      => 'Sangat enak!',
            'is_approved' => false,
        ]);

        $review = ProductReview::where('product_id', $product->id)->first();
        $review->approve();

        $product->refresh();
        $this->assertEquals(5.0, (float) $product->rating_average);
        $this->assertEquals(1, $product->review_count);
    }

    public function test_reject_review_does_not_count_in_rating(): void
    {
        $product  = Product::factory()->create();
        $customer = Customer::factory()->create();

        $review = ProductReview::create([
            'product_id'  => $product->id,
            'customer_id' => $customer->id,
            'rating'      => 1,
            'review'      => 'Buruk',
            'is_approved' => true,
        ]);

        $review->reject();

        $product->refresh();
        $this->assertEquals(0, $product->review_count);
    }

    public function test_only_approved_reviews_shown(): void
    {
        $product   = Product::factory()->create();
        $customer1 = Customer::factory()->create();
        $customer2 = Customer::factory()->create();

        ProductReview::create(['product_id' => $product->id, 'customer_id' => $customer1->id, 'rating' => 5, 'is_approved' => true]);
        ProductReview::create(['product_id' => $product->id, 'customer_id' => $customer2->id, 'rating' => 1, 'is_approved' => false]);

        $approved = ProductReview::where('product_id', $product->id)->where('is_approved', true)->count();
        $this->assertEquals(1, $approved);
    }

    public function test_average_rating_calculated_correctly(): void
    {
        $product   = Product::factory()->create();
        $customers = Customer::factory()->count(3)->create();

        foreach ([4, 5, 3] as $i => $rating) {
            ProductReview::create([
                'product_id'  => $product->id,
                'customer_id' => $customers[$i]->id,
                'rating'      => $rating,
                'is_approved' => true,
            ]);
        }

        $product->updateRating();
        $this->assertEquals(4.0, round((float) $product->fresh()->rating_average, 1));
    }
}
