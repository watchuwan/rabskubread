<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoyaltyPoint extends Model
{
    protected $fillable = [
        'customer_id',
        'points',
        'type',
        'reference_type',
        'reference_id',
        'description',
        'expires_at',
    ];

    protected $casts = [
        'points' => 'integer',
        'expires_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public static function earn(int $customerId, int $points, string $type, ?string $description = null, ?int $referenceId = null): self
    {
        return self::create([
            'customer_id' => $customerId,
            'points' => abs($points),
            'type' => $type,
            'reference_type' => 'earn',
            'reference_id' => $referenceId,
            'description' => $description,
            'expires_at' => now()->addYear(),
        ]);
    }

    public static function redeem(int $customerId, int $points, ?string $description = null): self
    {
        return self::create([
            'customer_id' => $customerId,
            'points' => -abs($points),
            'type' => 'redemption',
            'reference_type' => 'redeem',
            'description' => $description,
        ]);
    }

    public static function getBalance(int $customerId): int
    {
        return self::where('customer_id', $customerId)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->sum('points');
    }
}
