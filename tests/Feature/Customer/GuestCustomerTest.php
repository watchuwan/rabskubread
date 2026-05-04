<?php

namespace Tests\Feature\Customer;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestCustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_loads(): void
    {
        $this->get('/')->assertStatus(200);
    }

    public function test_products_list_loads(): void
    {
        Product::factory()->count(3)->create();
        $this->get('/products')->assertStatus(200);
    }

    public function test_product_detail_loads(): void
    {
        $product = Product::factory()->create();
        $this->get("/products/{$product->slug}")->assertStatus(200);
    }

    public function test_about_page_loads(): void
    {
        $this->get('/about')->assertStatus(200);
    }

    public function test_contact_page_loads(): void
    {
        $this->get('/contact')->assertStatus(200);
    }

    public function test_guest_redirected_from_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_guest_redirected_from_orders(): void
    {
        $this->get('/orders')->assertRedirect('/login');
    }

    public function test_guest_redirected_from_checkout(): void
    {
        $this->get('/checkout')->assertRedirect('/login');
    }

    public function test_guest_redirected_from_wishlist(): void
    {
        $this->get('/wishlist')->assertRedirect('/login');
    }

    public function test_products_filterable_by_category(): void
    {
        $category = Category::factory()->create();
        Product::factory()->count(3)->create(['category_id' => $category->id]);

        $this->get("/products?category={$category->slug}")->assertStatus(200);
    }
}
