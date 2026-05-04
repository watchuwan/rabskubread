<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, SoftDeletes, CanResetPassword;

    protected $guard = 'customer';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'is_active',
        'last_login_at',
        'birth_date',
        'gender',
        'referral_code',
        'referred_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_at' => 'datetime',
        'birth_date' => 'date',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($customer) {
            if (!$customer->referral_code) {
                $customer->referral_code = strtoupper(substr(md5(uniqid()), 0, 8));
            }
        });
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return false;
    }

    public function getGuardName(): string
    {
        return 'customer';
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\CustomerResetPasswordNotification($token));
    }

    // Relations
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    public function voucherUsages(): HasMany
    {
        return $this->hasMany(VoucherUsage::class);
    }

    public function socialiteAccounts(): HasMany
    {
        return $this->hasMany(SocialiteUser::class, 'customer_id');
    }

    public function comparisons(): HasMany
    {
        return $this->hasMany(ProductComparison::class);
    }

    public function loyaltyPoints(): HasMany
    {
        return $this->hasMany(LoyaltyPoint::class);
    }

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'referred_by');
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    // Accessors
    public function getLoyaltyBalanceAttribute(): int
    {
        return LoyaltyPoint::getBalance($this->id);
    }

    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    public function getTotalOrdersAttribute(): int
    {
        return $this->orders()->count();
    }

    public function getTotalSpentAttribute(): float
    {
        return $this->orders()->where('status', 'completed')->sum('total_amount');
    }

    // Methods
    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }

    public function hasActiveOrders(): bool
    {
        return $this->orders()->whereIn('status', ['pending', 'processing', 'shipped'])->exists();
    }

    public function hasOAuthProvider(string $provider): bool
    {
        return $this->socialiteAccounts()->where('provider', $provider)->exists();
    }

    public function getOAuthAccount(string $provider): ?SocialiteUser
    {
        return $this->socialiteAccounts()->where('provider', $provider)->first();
    }
}
