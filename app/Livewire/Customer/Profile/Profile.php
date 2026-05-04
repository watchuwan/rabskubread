<?php

namespace App\Livewire\Customer\Profile;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.customer')]
class Profile extends Component
{
    use WithFileUploads;

    #[Rule('nullable|image|max:2048')] // Max 2MB
    public $avatar;

    #[Rule('required|string|max:255')]
    public string $name = '';

    #[Rule('required|email')]
    public string $email = '';

    #[Rule('nullable|string|max:20')]
    public ?string $phone = null;

    #[Rule('nullable|date')]
    public ?string $birth_date = null;

    #[Rule('nullable|in:male,female,other')]
    public ?string $gender = null;

    public int $totalOrders = 0;
    public int $totalWishlist = 0;
    public int $totalReviews = 0;

    public function mount(): void
    {
        $customer = auth()->guard('customer')->user();

        $this->name = $customer->name;
        $this->email = $customer->email;
        $this->phone = $customer->phone;
        $this->birth_date = $customer->birth_date?->format('Y-m-d');
        $this->gender = $customer->gender;
        $this->avatar = $customer->avatar;
        
        // Load stats
        $this->totalOrders = $customer->total_orders;
        $this->totalWishlist = $customer->wishlists()->count();
        $this->totalReviews = $customer->reviews()->count();
    }

    public function updateAvatar(): void
    {
        $this->validate([
            'avatar' => 'image|max:2048', // 2MB max
        ]);

        $customer = auth()->guard('customer')->user();

        if ($this->avatar) {
            // Delete old avatar
            if ($customer->avatar && Storage::disk('public')->exists($customer->avatar)) {
                Storage::disk('public')->delete($customer->avatar);
            }

            // Store new avatar
            $path = $this->avatar->store('avatars', 'public');
            $customer->update(['avatar' => $path]);
            
            $this->avatar = $path;
        }

        $this->dispatch('toast', message: 'Avatar berhasil diupdate', type: 'success');
    }

    public function removeAvatar(): void
    {
        $customer = auth()->guard('customer')->user();

        if ($customer->avatar && Storage::disk('public')->exists($customer->avatar)) {
            Storage::disk('public')->delete($customer->avatar);
        }

        $customer->update(['avatar' => null]);
        $this->avatar = null;

        $this->dispatch('toast', message: 'Avatar berhasil dihapus', type: 'info');
    }

    public function updateProfile(): void
    {
        $validated = $this->validate();

        $customer = auth()->guard('customer')->user();

        $customer->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'birth_date' => $this->birth_date,
            'gender' => $this->gender,
        ]);

        $this->dispatch('toast', message: 'Profil berhasil diupdate', type: 'success');
    }

    public function render()
    {
        $customer = auth()->guard('customer')->user();
        
        return view('livewire.customer.profile.profile', [
            'customer' => $customer,
        ]);
    }
}
