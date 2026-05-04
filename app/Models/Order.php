<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_PENDING = "pending";
    const STATUS_PROCESSING = "processing";
    const STATUS_SHIPPED = "shipped";
    const STATUS_COMPLETED = "completed";
    const STATUS_CANCELLED = "cancelled";
    const STATUS_REFUNDED = "refunded";

    protected $fillable = [
        "customer_id",
        "address_id",
        "shipping_method_id",
        "voucher_id",
        "order_number",
        "status",
        "notes",
        "subtotal",
        "shipping_cost",
        "voucher_discount",
        "total_amount",
        "paid_at",
        "shipped_at",
        "completed_at",
        "cancelled_at",
    ];

    protected $casts = [
        "subtotal" => "decimal:2",
        "shipping_cost" => "decimal:2",
        "voucher_discount" => "decimal:2",
        "total_amount" => "decimal:2",
        "paid_at" => "datetime",
        "shipped_at" => "datetime",
        "completed_at" => "datetime",
        "cancelled_at" => "datetime",
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = "ORD-" . strtoupper(uniqid());
            }
        });

        static::updating(function ($order) {
            if ($order->isDirty("status")) {
                OrderStatusHistory::record($order, $order->status);

                // Auto-earn loyalty points when order completed
                if (
                    $order->status === self::STATUS_COMPLETED &&
                    $order->getOriginal("status") !== self::STATUS_COMPLETED
                ) {
                    $points = (int) floor($order->total_amount / 10000); // 1 point per 10k
                    if ($points > 0) {
                        LoyaltyPoint::earn(
                            $order->customer_id,
                            $points,
                            "order_completed",
                            "Order #{$order->order_number}",
                            $order->id,
                        );
                    }
                }
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function shippingMethod(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => "warning",
            self::STATUS_PROCESSING => "info",
            self::STATUS_SHIPPED => "primary",
            self::STATUS_COMPLETED => "success",
            self::STATUS_CANCELLED, self::STATUS_REFUNDED => "danger",
            default => "gray",
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => "Pending",
            self::STATUS_PROCESSING => "Processing",
            self::STATUS_SHIPPED => "Shipped",
            self::STATUS_COMPLETED => "Completed",
            self::STATUS_CANCELLED => "Cancelled",
            self::STATUS_REFUNDED => "Refunded",
            default => "Unknown",
        };
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where("status", $status);
    }

    public function scopeForCustomer($query, int $customerId)
    {
        return $query->where("customer_id", $customerId);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where("created_at", ">=", now()->subDays($days));
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_PROCESSING,
        ]);
    }
}
