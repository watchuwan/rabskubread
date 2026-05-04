<?php

namespace Tests\Feature\Customer;

use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\PromotionItem;
use App\Models\ShippingMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CartCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->customer = Customer::factory()->create();
    }

    // --- CART ---

    public function test_cart_created_for_customer(): void
    {
        $cart = Cart::getOrCreateForCustomer($this->customer->id);
        $this->assertDatabaseHas('carts', ['customer_id' => $this->customer->id]);
        $this->assertEquals($this->customer->id, $cart->customer_id);
    }

    public function test_add_product_to_cart(): void
    {
        $product = Product::factory()->create(['price' => 10000]);
        $cart    = Cart::getOrCreateForCustomer($this->customer->id);

        CartItem::updateOrCreate(
            ['cart_id' => $cart->id, 'product_id' => $product->id],
            ['quantity' => 2]
        );

        $this->assertDatabaseHas('cart_items', [
            'cart_id'    => $cart->id,
            'product_id' => $product->id,
            'quantity'   => 2,
        ]);
    }

    public function test_adding_same_product_increments_quantity(): void
    {
        $product = Product::factory()->create();
        $cart    = Cart::getOrCreateForCustomer($this->customer->id);

        CartItem::updateOrCreate(['cart_id' => $cart->id, 'product_id' => $product->id], ['quantity' => 1]);
        CartItem::updateOrCreate(['cart_id' => $cart->id, 'product_id' => $product->id], ['quantity' => 3]);

        $this->assertEquals(3, CartItem::where('cart_id', $cart->id)->where('product_id', $product->id)->value('quantity'));
    }

    public function test_remove_item_from_cart(): void
    {
        $product  = Product::factory()->create();
        $cart     = Cart::getOrCreateForCustomer($this->customer->id);
        $cartItem = CartItem::create(['cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => 1]);

        $cartItem->delete();

        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
    }

    // --- CHECKOUT (Livewire) ---

    public function test_checkout_page_loads_for_authenticated_customer(): void
    {
        $shipping = ShippingMethod::factory()->create(['is_active' => true]);
        $product  = Product::factory()->create(['stock' => 10]);
        $product->shippingMethods()->attach($shipping->id);

        $cart = Cart::getOrCreateForCustomer($this->customer->id);
        CartItem::create(['cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => 1]);

        Address::factory()->create(['customer_id' => $this->customer->id, 'is_default' => true]);

        $this->actingAs($this->customer, 'customer')
            ->get('/checkout')
            ->assertStatus(200);
    }

    public function test_checkout_redirects_if_cart_empty(): void
    {
        $this->actingAs($this->customer, 'customer')
            ->get('/checkout')
            ->assertRedirect('/cart');
    }

    public function test_place_order_creates_order_and_items(): void
    {
        $shipping = ShippingMethod::factory()->create(['cost' => 10000, 'is_active' => true]);
        $product  = Product::factory()->create(['price' => 50000, 'stock' => 10]);
        $product->shippingMethods()->attach($shipping->id);
        $address  = Address::factory()->create(['customer_id' => $this->customer->id, 'is_default' => true]);

        $cart = Cart::getOrCreateForCustomer($this->customer->id);
        CartItem::create(['cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => 2]);

        Livewire::actingAs($this->customer, 'customer')
            ->test(\App\Livewire\Customer\Order\Checkout::class)
            ->set('selectedAddress', $address->id)
            ->set('selectedShippingMethod', $shipping->id)
            ->set('agreeTerms', true)
            ->call('placeOrder');

        $this->assertDatabaseHas('orders', ['customer_id' => $this->customer->id]);
        $this->assertDatabaseHas('order_items', ['product_id' => $product->id, 'quantity' => 2]);
    }

    public function test_place_order_decrements_stock(): void
    {
        $shipping = ShippingMethod::factory()->create(['cost' => 10000, 'is_active' => true]);
        $product  = Product::factory()->create(['price' => 50000, 'stock' => 10]);
        $product->shippingMethods()->attach($shipping->id);
        $address  = Address::factory()->create(['customer_id' => $this->customer->id, 'is_default' => true]);

        $cart = Cart::getOrCreateForCustomer($this->customer->id);
        CartItem::create(['cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => 3]);

        Livewire::actingAs($this->customer, 'customer')
            ->test(\App\Livewire\Customer\Order\Checkout::class)
            ->set('selectedAddress', $address->id)
            ->set('selectedShippingMethod', $shipping->id)
            ->set('agreeTerms', true)
            ->call('placeOrder');

        $this->assertEquals(7, $product->fresh()->stock);
    }

    public function test_place_order_clears_cart(): void
    {
        $shipping = ShippingMethod::factory()->create(['cost' => 10000, 'is_active' => true]);
        $product  = Product::factory()->create(['price' => 50000, 'stock' => 10]);
        $product->shippingMethods()->attach($shipping->id);
        $address  = Address::factory()->create(['customer_id' => $this->customer->id, 'is_default' => true]);

        $cart = Cart::getOrCreateForCustomer($this->customer->id);
        CartItem::create(['cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => 1]);

        Livewire::actingAs($this->customer, 'customer')
            ->test(\App\Livewire\Customer\Order\Checkout::class)
            ->set('selectedAddress', $address->id)
            ->set('selectedShippingMethod', $shipping->id)
            ->set('agreeTerms', true)
            ->call('placeOrder');

        $this->assertEquals(0, $cart->fresh()->items()->count());
    }

    public function test_place_order_fails_if_stock_insufficient(): void
    {
        $shipping = ShippingMethod::factory()->create(['cost' => 10000, 'is_active' => true]);
        $product  = Product::factory()->create(['price' => 50000, 'stock' => 1]);
        $product->shippingMethods()->attach($shipping->id);
        $address  = Address::factory()->create(['customer_id' => $this->customer->id, 'is_default' => true]);

        $cart = Cart::getOrCreateForCustomer($this->customer->id);
        CartItem::create(['cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => 5]);

        Livewire::actingAs($this->customer, 'customer')
            ->test(\App\Livewire\Customer\Order\Checkout::class)
            ->set('selectedAddress', $address->id)
            ->set('selectedShippingMethod', $shipping->id)
            ->set('agreeTerms', true)
            ->call('placeOrder');

        $this->assertDatabaseMissing('orders', ['customer_id' => $this->customer->id]);
        $this->assertEquals(1, $product->fresh()->stock);
    }

    public function test_place_order_with_bundle_promotion_saves_promotion_price(): void
    {
        $shipping = ShippingMethod::factory()->create(['cost' => 0, 'is_active' => true]);
        $product  = Product::factory()->create(['price' => 12000, 'stock' => 20]);
        $product->shippingMethods()->attach($shipping->id);
        $address  = Address::factory()->create(['customer_id' => $this->customer->id, 'is_default' => true]);

        $promo = Promotion::factory()->bundle()->create(['price' => 100000, 'min_quantity' => 10]);
        PromotionItem::create(['promotion_id' => $promo->id, 'product_id' => $product->id, 'quantity' => 10]);

        $cart = Cart::getOrCreateForCustomer($this->customer->id);
        CartItem::create(['cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => 10]);

        Livewire::actingAs($this->customer, 'customer')
            ->test(\App\Livewire\Customer\Order\Checkout::class)
            ->set('selectedAddress', $address->id)
            ->set('selectedShippingMethod', $shipping->id)
            ->set('agreeTerms', true)
            ->call('placeOrder');

        $order = Order::where('customer_id', $this->customer->id)->first();
        $this->assertNotNull($order);

        $orderItem = $order->items()->where('product_id', $product->id)->first();
        $this->assertEquals($promo->id, $orderItem->promotion_id);
        // promotion_price per unit = 100000/10 = 10000
        $this->assertEquals(10000, $orderItem->price);
        // subtotal = 10 × 10000 = 100000
        $this->assertEquals(100000, $orderItem->subtotal);
    }
}
