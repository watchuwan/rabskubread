<?php

namespace App\Livewire\Customer\Referral;

use Livewire\Component;
use Livewire\WithPagination;

class ReferralPage extends Component
{
    use WithPagination;

    public $referralCode;

    public function mount()
    {
        $this->referralCode = auth()->user()->referral_code;
    }

    public function copyCode()
    {
        $this->dispatch('code-copied');
    }

    public function render()
    {
        $customer = auth()->user();
        
        $referrals = $customer->referrals()
            ->with('referred')
            ->orderByDesc('created_at')
            ->paginate(20);

        $stats = [
            'total' => $customer->referrals()->count(),
            'rewarded' => $customer->referrals()->where('is_rewarded', true)->count(),
            'pending' => $customer->referrals()->where('is_rewarded', false)->count(),
            'total_points' => $customer->referrals()->sum('reward_points'),
        ];

        return view('livewire.customer.referral.referral-page', [
            'referrals' => $referrals,
            'stats' => $stats,
        ])->layout('layouts.customer');
    }
}
