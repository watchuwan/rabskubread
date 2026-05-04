<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'promotion_id',
        'quantity',
        'promotion_price',
    ];

    protected $casts = [
        'quantity'        => 'integer',
        'promotion_price' => 'decimal:2',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    public function getSubtotalAttribute(): float
    {
        $unitPrice = $this->promotion_price ?? $this->product->price;
        return $unitPrice * $this->quantity;
    }

    public function isInStock(): bool
    {
        return $this->product->stock >= $this->quantity;
    }
}
