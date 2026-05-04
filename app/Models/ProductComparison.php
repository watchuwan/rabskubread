<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductComparison extends Model
{
    protected $fillable = ['customer_id', 'product_id'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public static function toggle(int $customerId, int $productId): bool
    {
        $exists = self::where('customer_id', $customerId)
            ->where('product_id', $productId)
            ->first();

        if ($exists) {
            $exists->delete();
            return false;
        }

        self::create(['customer_id' => $customerId, 'product_id' => $productId]);
        return true;
    }

    public static function getForCustomer(int $customerId)
    {
        return self::where('customer_id', $customerId)
            ->with('product.category')
            ->get();
    }
}
