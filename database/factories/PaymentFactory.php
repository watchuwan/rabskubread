<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_id'       => Order::factory(),
            'payment_number' => 'PAY-' . strtoupper(uniqid()),
            'amount'         => fake()->numberBetween(50000, 500000),
            'status'         => 'pending',
            'snap_token'     => null,
            'midtrans_transaction_id' => null,
            'midtrans_response' => null,
            'paid_at'        => null,
        ];
    }

    public function success(): static
    {
        return $this->state([
            'status'  => 'success',
            'paid_at' => now(),
        ]);
    }
}
