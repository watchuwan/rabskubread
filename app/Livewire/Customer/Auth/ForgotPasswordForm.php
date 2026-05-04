<?php

namespace App\Livewire\Customer\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.customer')]
class ForgotPasswordForm extends Component
{
    public string $email = '';
    public ?string $resetLink = null;

    protected $rules = [
        'email' => 'required|email|exists:customers,email',
    ];

    protected $messages = [
        'email.required' => 'Email wajib diisi',
        'email.email' => 'Format email tidak valid',
        'email.exists' => 'Email tidak terdaftar',
    ];

    public function sendResetLink(): void
    {
        $this->validate();

        $status = Password::broker('customers')->sendResetLink(
            ['email' => $this->email]
        );

        if ($status === Password::RESET_LINK_SENT) {
            // Jika mail driver = log, generate link untuk development
            if (config('mail.default') === 'log') {
                $customer = \App\Models\Customer::where('email', $this->email)->first();
                $token = Password::broker('customers')->createToken($customer);
                $this->resetLink = url(route('password.reset', [
                    'token' => $token,
                    'email' => $this->email,
                ], false));
                
                $this->dispatch('toast', message: 'Link reset password berhasil dibuat (lihat di bawah)', type: 'success');
            } else {
                $this->dispatch('toast', message: 'Link reset password telah dikirim ke email Anda', type: 'success');
                $this->email = '';
            }
        } else {
            $this->dispatch('toast', message: 'Gagal mengirim link reset password', type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.customer.auth.forgot-password-form');
    }
}
