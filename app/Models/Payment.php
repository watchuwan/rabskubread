<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    const STATUS_PENDING = "pending";
    const STATUS_SUCCESS = "success";
    const STATUS_FAILED = "failed";
    const STATUS_EXPIRED = "expired";
    const STATUS_REFUNDED = "refunded";

    protected $fillable = [
        "order_id",
        "payment_number",
        "amount",
        "status",
        "midtrans_transaction_id",
        "snap_token",
        "midtrans_response",
        "paid_at",
    ];

    protected $casts = [
        "amount" => "decimal:2",
        "midtrans_response" => "array",
        "paid_at" => "datetime",
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($payment) {
            if (!$payment->payment_number) {
                $payment->payment_number = "PAY-" . strtoupper(uniqid());
            }
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => "warning",
            self::STATUS_SUCCESS => "success",
            self::STATUS_FAILED, self::STATUS_EXPIRED => "danger",
            self::STATUS_REFUNDED => "info",
            default => "gray",
        };
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isSuccess(): bool
    {
        return $this->status === self::STATUS_SUCCESS;
    }

    public function isFailed(): bool
    {
        return in_array($this->status, [
            self::STATUS_FAILED,
            self::STATUS_EXPIRED,
        ]);
    }
}
