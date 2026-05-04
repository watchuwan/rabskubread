<?php

namespace Database\Seeders;

use App\Models\{Address, Customer, Order, OrderItem, Product, ShippingMethod};
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::limit(3)->get();
        $shippingMethod = ShippingMethod::first();
        $products = Product::all();

        $statuses = ['pending', 'processing', 'shipped', 'completed'];

        foreach ($customers as $customer) {
            $address = Address::where('customer_id', $customer->id)->first();
            if (!$address) continue;

            // Create 2-3 orders per customer
            for ($i = 0; $i < rand(2, 3); $i++) {
                $orderProducts = $products->random(rand(2, 4));
                $subtotal = 0;

                $order = Order::create([
                    'customer_id' => $customer->id,
                    'address_id' => $address->id,
                    'shipping_method_id' => $shippingMethod->id,
                    'status' => $statuses[array_rand($statuses)],
                    'subtotal' => 0,
                    'shipping_cost' => $shippingMethod->cost,
                    'voucher_discount' => 0,
                    'total_amount' => 0,
                ]);

                foreach ($orderProducts as $product) {
                    $quantity = rand(1, 3);
                    $itemSubtotal = $product->price * $quantity;
                    $subtotal += $itemSubtotal;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $product->price,
                        'subtotal' => $itemSubtotal,
                    ]);
                }

                $order->update([
                    'subtotal' => $subtotal,
                    'total_amount' => $subtotal + $shippingMethod->cost,
                ]);
            }
        }
    }
}
