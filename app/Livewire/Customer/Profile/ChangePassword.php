<?php

namespace App\Livewire\Customer\Profile;

use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('layouts.customer')]
class ChangePassword extends Component
{
    #[Rule('required|min:6')]
    public string $current_password = '';

    #[Rule('required|min:6|confirmed')]
    public string $password = '';

    public string $password_confirmation = '';

    public function updatePassword(): void
    {
        $this->validate();

        $customer = auth()->guard('customer')->user();

        if (!Hash::check($this->current_password, $customer->password)) {
            $this->addError('current_password', 'Password saat ini salah');
            return;
        }

        $customer->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        
        $this->dispatch('toast', message: 'Password berhasil diubah', type: 'success');
    }

    public function render()
    {
        return view('livewire.customer.profile.change-password');
    }
}
