<?php

namespace App\Livewire\Customer\Auth;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.customer')]
class LoginForm extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|min:6')]
    public string $password = '';

    public bool $remember = false;

    public function mount(): void
    {
        if (auth()->guard('customer')->check()) {
            $this->redirect(route('home'), navigate: true);
        }
    }

    public function login(): void
    {
        $this->validate();

        if (auth()->guard('customer')->attempt([
            'email' => $this->email,
            'password' => $this->password,
        ], $this->remember)) {
            session()->regenerate();

            $customer = auth()->guard('customer')->user();
            
            if (!$customer->is_active) {
                auth()->guard('customer')->logout();
                $this->addError('email', __('Akun Anda tidak aktif. Silakan hubungi dukungan.'));
                return;
            }

            $customer->update(['last_login_at' => now()]);

            $this->redirectIntended(route('home'), navigate: true);
            return;
        }

        $this->addError('email', __('Email atau password salah.'));
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.customer.auth.login-form');
    }
}
