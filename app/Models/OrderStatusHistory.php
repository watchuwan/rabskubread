<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderStatusHistory extends Model
{
    protected $fillable = [
        'order_id',
        'from_status',
        'to_status',
        'notes',
        'user_id',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function record(Order $order, string $toStatus, ?string $notes = null): self
    {
        // Get user_id only from admin guard, null if customer
        $userId = auth()->guard('web')->check() ? auth()->guard('web')->id() : null;
        
        return self::create([
            'order_id' => $order->id,
            'from_status' => $order->getOriginal('status'),
            'to_status' => $toStatus,
            'notes' => $notes,
            'user_id' => $userId,
        ]);
    }
}
