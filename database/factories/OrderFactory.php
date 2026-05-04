<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Customer;
use App\Models\ShippingMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $subtotal = fake()->numberBetween(50000, 500000);
        $shipping = 10000;

        return [
            'customer_id'       => Customer::factory(),
            'address_id'        => Address::factory(),
            'shipping_method_id' => ShippingMethod::factory(),
            'order_number'      => 'ORD-' . strtoupper(uniqid()),
            'status'            => 'pending',
            'subtotal'          => $subtotal,
            'shipping_cost'     => $shipping,
            'voucher_discount'  => 0,
            'total_amount'      => $subtotal + $shipping,
        ];
    }

    public function pending(): static   { return $this->state(['status' => 'pending']); }
    public function processing(): static { return $this->state(['status' => 'processing']); }
    public function shipped(): static   { return $this->state(['status' => 'shipped']); }
    public function completed(): static { return $this->state(['status' => 'completed', 'completed_at' => now()]); }
    public function cancelled(): static { return $this->state(['status' => 'cancelled', 'cancelled_at' => now()]); }
}
