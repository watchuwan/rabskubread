<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect to provider
     */
    public function redirect(string $provider): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle provider callback
     */
    public function callback(string $provider): RedirectResponse
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            
            // Find existing customer by socialite account
            $socialiteAccount = \App\Models\SocialiteUser::where('provider', $provider)
                ->where('provider_id', $socialUser->getId())
                ->first();
            
            if ($socialiteAccount) {
                // Login existing customer
                $customer = $socialiteAccount->customer;
                
                if (!$customer->is_active) {
                    return redirect()->route('login')
                        ->withErrors(['email' => __('Akun Anda tidak aktif. Silakan hubungi dukungan.')]);
                }
                
                Auth::guard('customer')->login($customer);
                $customer->update(['last_login_at' => now()]);
                
                return redirect()->intended(route('home'));
            }
            
            // Find existing customer by email
            $customer = Customer::where('email', $socialUser->getEmail())->first();
            
            if ($customer) {
                // Link social account to existing customer
                $customer->socialiteAccounts()->create([
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'avatar' => $socialUser->getAvatar(),
                    'metadata' => json_encode([
                        'name' => $socialUser->getName(),
                        'nickname' => $socialUser->getNickname(),
                    ]),
                ]);
                
                if (!$customer->is_active) {
                    return redirect()->route('login')
                        ->withErrors(['email' => __('Akun Anda tidak aktif. Silakan hubungi dukungan.')]);
                }
                
                Auth::guard('customer')->login($customer);
                $customer->update(['last_login_at' => now()]);
                
                return redirect()->intended(route('home'));
            }
            
            // Create new customer
            $customer = Customer::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'avatar' => $socialUser->getAvatar(),
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
            
            // Link social account
            $customer->socialiteAccounts()->create([
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
                'metadata' => json_encode([
                    'name' => $socialUser->getName(),
                    'nickname' => $socialUser->getNickname(),
                ]),
            ]);
            
            Auth::guard('customer')->login($customer);
            
            return redirect()->intended(route('home'));
            
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['email' => __('Gagal masuk dengan ' . ucfirst($provider) . '. Silakan coba lagi.')]);
        }
    }
}
