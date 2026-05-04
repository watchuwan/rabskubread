<?php

namespace App\Services;

use App\Models\Promotion;
use Illuminate\Support\Collection;

class PromotionService
{
    /**
     * Apply promotions to cart items collection.
     * Returns the items with promotion_price set where applicable,
     * and the total promotion discount amount.
     *
     * @param  Collection  $cartItems  (each item must have product, quantity)
     * @return array{items: Collection, promotion_discount: float}
     */
    public function applyToCartItems(Collection $cartItems): array
    {
        $promotions = Promotion::valid()->with('items.product')->get();

        // Reset promotion on all items first
        $cartItems = $cartItems->map(function ($item) {
            $item->promotion_id    = null;
            $item->promotion_price = null;
            return $item;
        });

        foreach ($promotions as $promo) {
            if ($promo->type === 'bundle') {
                $cartItems = $this->applyBundle($cartItems, $promo);
            } elseif ($promo->type === 'package') {
                $cartItems = $this->applyPackage($cartItems, $promo);
            }
        }

        // Calculate discount = original price - promo price for affected items
        $promotionDiscount = $cartItems->sum(function ($item) {
            if ($item->promotion_price !== null) {
                $original = $item->product->price * $item->quantity;
                return max(0, $original - ($item->promotion_price * $item->quantity));
            }
            return 0;
        });

        return [
            'items'               => $cartItems,
            'promotion_discount'  => $promotionDiscount,
        ];
    }

    /**
     * Bundle: 1 produk, beli >= min_quantity → harga per unit = promo->price / min_quantity
     */
    private function applyBundle(Collection $cartItems, Promotion $promo): Collection
    {
        $promoProductId = $promo->items->first()?->product_id;
        if (! $promoProductId) return $cartItems;

        return $cartItems->map(function ($item) use ($promo, $promoProductId) {
            if ($item->product_id === $promoProductId && $item->quantity >= $promo->min_quantity) {
                // How many full bundles fit
                $bundles       = intdiv($item->quantity, $promo->min_quantity);
                $remainder     = $item->quantity % $promo->min_quantity;
                $bundleTotal   = $bundles * $promo->price;
                $remainderTotal = $remainder * $item->product->price;
                // Effective price per unit
                $item->promotion_price = ($bundleTotal + $remainderTotal) / $item->quantity;
                $item->promotion_id    = $promo->id;
            }
            return $item;
        });
    }

    /**
     * Package: beberapa produk dengan qty tertentu → harga paket dibagi proporsional per produk.
     * Semua produk dalam paket harus ada di cart dengan qty >= yang ditentukan.
     */
    private function applyPackage(Collection $cartItems, Promotion $promo): Collection
    {
        $promoItems = $promo->items->keyBy('product_id');

        // Check if all package products are in cart with sufficient qty
        foreach ($promoItems as $productId => $promoItem) {
            $cartItem = $cartItems->firstWhere('product_id', $productId);
            if (! $cartItem || $cartItem->quantity < $promoItem->quantity) {
                return $cartItems; // package not fulfilled
            }
        }

        // Calculate how many full packages can be applied
        $packageMultiplier = $promoItems->min(function ($promoItem) use ($cartItems) {
            $cartItem = $cartItems->firstWhere('product_id', $promoItem->product_id);
            return intdiv($cartItem->quantity, $promoItem->quantity);
        });

        if ($packageMultiplier < 1) return $cartItems;

        // Original total of package products (for proportional price split)
        $originalPackageTotal = $promoItems->sum(
            fn ($pi) => $pi->product->price * $pi->quantity
        );

        return $cartItems->map(function ($item) use ($promo, $promoItems, $packageMultiplier, $originalPackageTotal) {
            $promoItem = $promoItems->get($item->product_id);
            if (! $promoItem) return $item;

            $packageQty   = $promoItem->quantity * $packageMultiplier;
            $remainderQty = $item->quantity - $packageQty;

            // Proportional share of package price for this product
            $productShare     = ($item->product->price * $promoItem->quantity) / $originalPackageTotal;
            $promoUnitPrice   = ($promo->price * $productShare) / $promoItem->quantity;

            $packageTotal   = $promoUnitPrice * $packageQty;
            $remainderTotal = $item->product->price * $remainderQty;

            $item->promotion_price = ($packageTotal + $remainderTotal) / $item->quantity;
            $item->promotion_id    = $promo->id;

            return $item;
        });
    }
}
