<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Referral extends Model
{
    protected $fillable = [
        'referrer_id',
        'referred_id',
        'reward_points',
        'is_rewarded',
        'rewarded_at',
    ];

    protected $casts = [
        'reward_points' => 'integer',
        'is_rewarded' => 'boolean',
        'rewarded_at' => 'datetime',
    ];

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'referrer_id');
    }

    public function referred(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'referred_id');
    }

    public function giveReward(int $points = 100): void
    {
        if (!$this->is_rewarded) {
            LoyaltyPoint::earn(
                $this->referrer_id,
                $points,
                'referral_reward',
                "Referral reward for {$this->referred->name}",
                $this->id
            );

            $this->update([
                'reward_points' => $points,
                'is_rewarded' => true,
                'rewarded_at' => now(),
            ]);
        }
    }
}
