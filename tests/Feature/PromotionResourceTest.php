<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\PromotionItem;
use App\Models\User;
use App\Services\PromotionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromotionResourceTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
        $this->admin->assignRole('super_admin');
    }

    public function test_admin_can_access_promotions_list(): void
    {
        $this->actingAs($this->admin)
            ->get('/admin/promotions')
            ->assertStatus(200);
    }

    public function test_promotion_slug_auto_generated(): void
    {
        $promo = Promotion::factory()->create(['name' => 'Bundle Roti Tawar']);
        $this->assertEquals('bundle-roti-tawar', $promo->slug);
    }

    public function test_valid_scope_excludes_expired_and_inactive(): void
    {
        Promotion::factory()->create(['is_active' => true, 'valid_until' => now()->addDay()]);
        Promotion::factory()->expired()->create();
        Promotion::factory()->inactive()->create();

        $this->assertEquals(1, Promotion::valid()->count());
    }

    public function test_bundle_scope_filters_by_type(): void
    {
        Promotion::factory()->bundle()->create();
        Promotion::factory()->bundle()->create();
        Promotion::factory()->package()->create();

        $this->assertEquals(2, Promotion::bundle()->count());
        $this->assertEquals(1, Promotion::package()->count());
    }

    public function test_promotion_service_applies_bundle_discount(): void
    {
        $product = Product::factory()->create(['price' => 12000]);

        $promo = Promotion::factory()->bundle()->create([
            'price'        => 100000,
            'min_quantity' => 10,
        ]);
        PromotionItem::create(['promotion_id' => $promo->id, 'product_id' => $product->id, 'quantity' => 10]);

        // Simulate cart item
        $cartItem = new \stdClass();
        $cartItem->product_id      = $product->id;
        $cartItem->product         = $product;
        $cartItem->quantity        = 10;
        $cartItem->promotion_id    = null;
        $cartItem->promotion_price = null;

        $result = (new PromotionService())->applyToCartItems(collect([$cartItem]));

        $this->assertEquals($promo->id, $result['items']->first()->promotion_id);
        $this->assertGreaterThan(0, $result['promotion_discount']);
        // 10 × 12000 = 120000 original, promo = 100000, discount = 20000
        $this->assertEquals(20000, $result['promotion_discount']);
    }

    public function test_promotion_service_no_discount_below_min_quantity(): void
    {
        $product = Product::factory()->create(['price' => 12000]);

        $promo = Promotion::factory()->bundle()->create([
            'price'        => 100000,
            'min_quantity' => 10,
        ]);
        PromotionItem::create(['promotion_id' => $promo->id, 'product_id' => $product->id, 'quantity' => 10]);

        $cartItem = new \stdClass();
        $cartItem->product_id      = $product->id;
        $cartItem->product         = $product;
        $cartItem->quantity        = 5; // below min_quantity
        $cartItem->promotion_id    = null;
        $cartItem->promotion_price = null;

        $result = (new PromotionService())->applyToCartItems(collect([$cartItem]));

        $this->assertNull($result['items']->first()->promotion_id);
        $this->assertEquals(0, $result['promotion_discount']);
    }

    public function test_promotion_has_items_relationship(): void
    {
        $product = Product::factory()->create();
        $promo   = Promotion::factory()->create();
        PromotionItem::create(['promotion_id' => $promo->id, 'product_id' => $product->id, 'quantity' => 5]);

        $this->assertEquals(1, $promo->items()->count());
        $this->assertEquals($product->id, $promo->items->first()->product_id);
    }

    public function test_promotion_service_bundle_with_remainder(): void
    {
        $product = Product::factory()->create(['price' => 10000]);
        $promo   = Promotion::factory()->bundle()->create(['price' => 80000, 'min_quantity' => 10]);
        PromotionItem::create(['promotion_id' => $promo->id, 'product_id' => $product->id, 'quantity' => 10]);

        $cartItem = new \stdClass();
        $cartItem->product_id      = $product->id;
        $cartItem->product         = $product;
        $cartItem->quantity        = 12; // 1 bundle (10) + 2 remainder
        $cartItem->promotion_id    = null;
        $cartItem->promotion_price = null;

        $result = (new PromotionService())->applyToCartItems(collect([$cartItem]));

        // 1 bundle = 80000, 2 remainder = 2×10000 = 20000, total = 100000
        // original = 12×10000 = 120000, discount = 20000
        $this->assertEquals(20000, $result['promotion_discount']);
    }

    public function test_promotion_service_applies_package_discount(): void
    {
        $productA = Product::factory()->create(['price' => 20000]);
        $productB = Product::factory()->create(['price' => 30000]);

        // Package: 1×A + 1×B = 40000 (original 50000)
        $promo = Promotion::factory()->package()->create(['price' => 40000, 'min_quantity' => 1]);
        PromotionItem::create(['promotion_id' => $promo->id, 'product_id' => $productA->id, 'quantity' => 1]);
        PromotionItem::create(['promotion_id' => $promo->id, 'product_id' => $productB->id, 'quantity' => 1]);

        $itemA = new \stdClass();
        $itemA->product_id = $productA->id; $itemA->product = $productA;
        $itemA->quantity = 1; $itemA->promotion_id = null; $itemA->promotion_price = null;

        $itemB = new \stdClass();
        $itemB->product_id = $productB->id; $itemB->product = $productB;
        $itemB->quantity = 1; $itemB->promotion_id = null; $itemB->promotion_price = null;

        $result = (new PromotionService())->applyToCartItems(collect([$itemA, $itemB]));

        // discount = 50000 - 40000 = 10000
        $this->assertEquals(10000, $result['promotion_discount']);
        $this->assertEquals($promo->id, $result['items']->first()->promotion_id);
    }

    public function test_promotion_service_package_not_applied_if_product_missing(): void
    {
        $productA = Product::factory()->create(['price' => 20000]);
        $productB = Product::factory()->create(['price' => 30000]);

        $promo = Promotion::factory()->package()->create(['price' => 40000, 'min_quantity' => 1]);
        PromotionItem::create(['promotion_id' => $promo->id, 'product_id' => $productA->id, 'quantity' => 1]);
        PromotionItem::create(['promotion_id' => $promo->id, 'product_id' => $productB->id, 'quantity' => 1]);

        // Only productA in cart, productB missing
        $itemA = new \stdClass();
        $itemA->product_id = $productA->id; $itemA->product = $productA;
        $itemA->quantity = 1; $itemA->promotion_id = null; $itemA->promotion_price = null;

        $result = (new PromotionService())->applyToCartItems(collect([$itemA]));

        $this->assertEquals(0, $result['promotion_discount']);
        $this->assertNull($result['items']->first()->promotion_id);
    }

    public function test_cart_item_subtotal_uses_promotion_price(): void
    {
        $product  = Product::factory()->create(['price' => 10000]);
        $cart     = \App\Models\Cart::getOrCreateForCustomer(\App\Models\Customer::factory()->create()->id);
        $cartItem = \App\Models\CartItem::create([
            'cart_id'         => $cart->id,
            'product_id'      => $product->id,
            'quantity'        => 3,
            'promotion_price' => 8000,
        ]);

        $this->assertEquals(24000, $cartItem->subtotal); // 3 × 8000
    }

    public function test_cart_item_subtotal_falls_back_to_product_price(): void
    {
        $product  = Product::factory()->create(['price' => 10000]);
        $cart     = \App\Models\Cart::getOrCreateForCustomer(\App\Models\Customer::factory()->create()->id);
        $cartItem = \App\Models\CartItem::create([
            'cart_id'    => $cart->id,
            'product_id' => $product->id,
            'quantity'   => 2,
        ]);

        $this->assertEquals(20000, $cartItem->subtotal); // 2 × 10000
    }
}
