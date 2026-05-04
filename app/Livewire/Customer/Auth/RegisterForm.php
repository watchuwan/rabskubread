<?php

namespace App\Livewire\Customer\Auth;

use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.customer')]
class RegisterForm extends Component
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|email|unique:customers,email')]
    public string $email = '';

    #[Validate('required|min:6|confirmed')]
    public string $password = '';

    public string $password_confirmation = '';

    #[Validate('accepted')]
    public bool $terms = false;

    public function mount(): void
    {
        if (auth()->guard('customer')->check()) {
            $this->redirect(route('home'), navigate: true);
        }
    }

    public function register(): void
    {
        $this->validate();

        $customer = Customer::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'is_active' => true,
        ]);

        auth()->guard('customer')->login($customer, remember: true);

        session()->regenerate();

        $this->redirect(route('home'), navigate: true);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.customer.auth.register-form');
    }
}
