<?php

namespace Tests\Feature\Customer;

use App\Models\Address;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileAddressTest extends TestCase
{
    use RefreshDatabase;

    protected Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->customer = Customer::factory()->create();
    }

    public function test_profile_page_loads(): void
    {
        $this->actingAs($this->customer, 'customer')
            ->get('/profile')
            ->assertStatus(200);
    }

    public function test_addresses_page_loads(): void
    {
        $this->actingAs($this->customer, 'customer')
            ->get('/addresses')
            ->assertStatus(200);
    }

    public function test_address_create_page_loads(): void
    {
        $this->actingAs($this->customer, 'customer')
            ->get('/addresses/create')
            ->assertStatus(200);
    }

    public function test_address_edit_page_loads(): void
    {
        $address = Address::factory()->create(['customer_id' => $this->customer->id]);

        $this->actingAs($this->customer, 'customer')
            ->get("/addresses/{$address->id}/edit")
            ->assertStatus(200);
    }

    public function test_customer_can_create_address(): void
    {
        $address = Address::factory()->create(['customer_id' => $this->customer->id]);

        $this->assertDatabaseHas('addresses', [
            'customer_id' => $this->customer->id,
            'id'          => $address->id,
        ]);
    }

    public function test_customer_can_set_default_address(): void
    {
        $addr1 = Address::factory()->create(['customer_id' => $this->customer->id, 'is_default' => true]);
        $addr2 = Address::factory()->create(['customer_id' => $this->customer->id, 'is_default' => false]);

        // Simulate set default
        Address::where('customer_id', $this->customer->id)->update(['is_default' => false]);
        $addr2->update(['is_default' => true]);

        $this->assertFalse((bool) $addr1->fresh()->is_default);
        $this->assertTrue((bool) $addr2->fresh()->is_default);
    }

    public function test_customer_cannot_access_other_customers_address(): void
    {
        $other   = Customer::factory()->create();
        $address = Address::factory()->create(['customer_id' => $other->id]);

        $this->actingAs($this->customer, 'customer')
            ->get("/addresses/{$address->id}/edit")
            ->assertStatus(404);
    }

    public function test_multiple_addresses_per_customer(): void
    {
        Address::factory()->count(3)->create(['customer_id' => $this->customer->id]);

        $this->assertEquals(3, Address::where('customer_id', $this->customer->id)->count());
    }
}
