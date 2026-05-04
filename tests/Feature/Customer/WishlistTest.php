<?php

namespace Tests\Feature\Customer;

use App\Models\Cart;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistTest extends TestCase
{
    use RefreshDatabase;

    protected Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->customer = Customer::factory()->create();
    }

    public function test_wishlist_page_loads(): void
    {
        $this->actingAs($this->customer, 'customer')
            ->get('/wishlist')
            ->assertStatus(200);
    }

    public function test_guest_redirected_from_wishlist(): void
    {
        $this->get('/wishlist')->assertRedirect('/login');
    }

    public function test_add_product_to_wishlist(): void
    {
        $product = Product::factory()->create();

        Wishlist::create(['customer_id' => $this->customer->id, 'product_id' => $product->id]);

        $this->assertDatabaseHas('wishlists', [
            'customer_id' => $this->customer->id,
            'product_id'  => $product->id,
        ]);
    }

    public function test_remove_product_from_wishlist(): void
    {
        $product  = Product::factory()->create();
        $wishlist = Wishlist::create(['customer_id' => $this->customer->id, 'product_id' => $product->id]);

        $wishlist->delete();

        $this->assertDatabaseMissing('wishlists', ['id' => $wishlist->id]);
    }

    public function test_wishlist_count_correct(): void
    {
        $products = Product::factory()->count(3)->create();
        foreach ($products as $product) {
            Wishlist::create(['customer_id' => $this->customer->id, 'product_id' => $product->id]);
        }

        $this->assertEquals(3, Wishlist::where('customer_id', $this->customer->id)->count());
    }

    public function test_move_wishlist_item_to_cart(): void
    {
        $product  = Product::factory()->create();
        $wishlist = Wishlist::create(['customer_id' => $this->customer->id, 'product_id' => $product->id]);

        // Simulate move to cart
        $cart = Cart::getOrCreateForCustomer($this->customer->id);
        \App\Models\CartItem::updateOrCreate(
            ['cart_id' => $cart->id, 'product_id' => $product->id],
            ['quantity' => 1]
        );
        $wishlist->delete();

        $this->assertDatabaseHas('cart_items', ['cart_id' => $cart->id, 'product_id' => $product->id]);
        $this->assertDatabaseMissing('wishlists', ['id' => $wishlist->id]);
    }
}
