<?php

namespace Tests\Feature\Customer;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\LoyaltyPoint;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class OrderFlowTest extends TestCase
{
    use RefreshDatabase;

    protected Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->customer = Customer::factory()->create();
    }

    public function test_order_history_page_loads(): void
    {
        Order::factory()->count(3)->create(['customer_id' => $this->customer->id]);

        $this->actingAs($this->customer, 'customer')
            ->get('/orders')
            ->assertStatus(200);
    }

    public function test_order_detail_page_loads(): void
    {
        $order = Order::factory()->create(['customer_id' => $this->customer->id]);

        $this->actingAs($this->customer, 'customer')
            ->get("/orders/{$order->id}")
            ->assertStatus(200);
    }

    public function test_customer_cannot_view_other_customers_order(): void
    {
        $other = Customer::factory()->create();
        $order = Order::factory()->create(['customer_id' => $other->id]);

        $this->actingAs($this->customer, 'customer')
            ->get("/orders/{$order->id}")
            ->assertStatus(403);
    }

    public function test_order_number_auto_generated(): void
    {
        $order = Order::factory()->create();
        $this->assertStringStartsWith('ORD-', $order->order_number);
    }

    public function test_cancel_pending_order(): void
    {
        $product = Product::factory()->create(['stock' => 10]);
        $order   = Order::factory()->create(['customer_id' => $this->customer->id, 'status' => 'pending']);
        OrderItem::create(['order_id' => $order->id, 'product_id' => $product->id, 'quantity' => 2, 'price' => 10000, 'subtotal' => 20000]);

        Livewire::actingAs($this->customer, 'customer')
            ->test(\App\Livewire\Customer\Order\OrderDetail::class, ['order' => $order])
            ->call('cancelOrder');

        $this->assertEquals('cancelled', $order->fresh()->status);
        $this->assertNotNull($order->fresh()->cancelled_at);
        $this->assertEquals(12, $product->fresh()->stock); // stock restored
    }

    public function test_cancel_processing_order(): void
    {
        $order = Order::factory()->create(['customer_id' => $this->customer->id, 'status' => 'processing']);

        Livewire::actingAs($this->customer, 'customer')
            ->test(\App\Livewire\Customer\Order\OrderDetail::class, ['order' => $order])
            ->call('cancelOrder');

        $this->assertEquals('cancelled', $order->fresh()->status);
    }

    public function test_cannot_cancel_completed_order(): void
    {
        $order = Order::factory()->create(['customer_id' => $this->customer->id, 'status' => 'completed']);

        Livewire::actingAs($this->customer, 'customer')
            ->test(\App\Livewire\Customer\Order\OrderDetail::class, ['order' => $order])
            ->call('cancelOrder');

        $this->assertEquals('completed', $order->fresh()->status);
    }

    public function test_reorder_adds_items_to_cart(): void
    {
        $product = Product::factory()->create();
        $order   = Order::factory()->create(['customer_id' => $this->customer->id, 'status' => 'completed']);
        OrderItem::create(['order_id' => $order->id, 'product_id' => $product->id, 'quantity' => 2, 'price' => 10000, 'subtotal' => 20000]);

        Livewire::actingAs($this->customer, 'customer')
            ->test(\App\Livewire\Customer\Order\OrderDetail::class, ['order' => $order])
            ->call('reorder');

        $cart = Cart::where('customer_id', $this->customer->id)->first();
        $this->assertNotNull($cart);
        $this->assertDatabaseHas('cart_items', ['cart_id' => $cart->id, 'product_id' => $product->id]);
    }

    public function test_order_status_history_recorded_on_status_change(): void
    {
        $order = Order::factory()->create(['status' => 'pending']);
        $order->update(['status' => 'processing']);

        $this->assertDatabaseHas('order_status_histories', [
            'order_id'    => $order->id,
            'from_status' => 'pending',
            'to_status'   => 'processing',
        ]);
    }

    public function test_loyalty_points_earned_when_order_completed(): void
    {
        $order = Order::factory()->create([
            'customer_id'  => $this->customer->id,
            'status'       => 'processing',
            'total_amount' => 100000,
        ]);

        $order->update(['status' => 'completed']);

        $points = LoyaltyPoint::where('customer_id', $this->customer->id)->sum('points');
        $this->assertEquals(10, $points); // floor(100000/10000) = 10
    }

    public function test_payment_number_auto_generated(): void
    {
        $payment = Payment::factory()->create();
        $this->assertStringStartsWith('PAY-', $payment->payment_number);
    }
}
