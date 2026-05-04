<?php

namespace Tests\Feature\Customer;

use App\Livewire\Customer\Auth\LoginForm;
use App\Livewire\Customer\Auth\RegisterForm;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class CustomerAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_customer(): void
    {
        Livewire::test(RegisterForm::class)
            ->set('name', 'John Doe')
            ->set('email', 'john@example.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->set('terms', true)
            ->call('register')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('customers', ['email' => 'john@example.com']);
    }

    public function test_register_generates_referral_code(): void
    {
        Livewire::test(RegisterForm::class)
            ->set('name', 'John Doe')
            ->set('email', 'john@example.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->set('terms', true)
            ->call('register');

        $customer = Customer::where('email', 'john@example.com')->first();
        $this->assertNotNull($customer?->referral_code);
        $this->assertEquals(8, strlen($customer->referral_code));
    }

    public function test_register_fails_with_duplicate_email(): void
    {
        Customer::factory()->create(['email' => 'existing@example.com']);

        Livewire::test(RegisterForm::class)
            ->set('name', 'John Doe')
            ->set('email', 'existing@example.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->call('register')
            ->assertHasErrors(['email']);
    }

    public function test_login_succeeds_with_valid_credentials(): void
    {
        $customer = Customer::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        Livewire::test(LoginForm::class)
            ->set('email', $customer->email)
            ->set('password', 'password123')
            ->call('login')
            ->assertHasNoErrors();

        $this->assertAuthenticatedAs($customer, 'customer');
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $customer = Customer::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        Livewire::test(LoginForm::class)
            ->set('email', $customer->email)
            ->set('password', 'wrongpassword')
            ->call('login')
            ->assertHasErrors();

        $this->assertGuest('customer');
    }

    public function test_inactive_customer_cannot_login(): void
    {
        $customer = Customer::factory()->create([
            'is_active' => false,
            'password'  => Hash::make('password123'),
        ]);

        Livewire::test(LoginForm::class)
            ->set('email', $customer->email)
            ->set('password', 'password123')
            ->call('login')
            ->assertHasErrors();

        $this->assertGuest('customer');
    }

    public function test_guest_redirected_from_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_authenticated_customer_redirected_from_login(): void
    {
        $customer = Customer::factory()->create();

        $this->actingAs($customer, 'customer')
            ->get('/login')
            ->assertRedirect(route('dashboard'));
    }
}
