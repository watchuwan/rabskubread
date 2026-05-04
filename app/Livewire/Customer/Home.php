<?php

namespace App\Livewire\Customer;

use App\Models\{Category, Customer, Product, ProductReview, Promotion, Voucher};
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout("layouts.customer")]
class Home extends Component
{
    public function render(): \Illuminate\View\View
    {
        $categories = Category::active()
            ->withCount("products")
            ->with("media")
            ->orderBy("sort_order")
            ->get();

        $featuredProducts = Product::active()
            ->inStock()
            ->with("category")
            ->orderBy("view_count", "desc")
            ->limit(8)
            ->get();

        $heroProducts = Product::active()
            ->inStock()
            ->orderBy("view_count", "desc")
            ->limit(3)
            ->get();

        $newProducts = Product::active()
            ->inStock()
            ->with("category")
            ->latest()
            ->limit(4)
            ->get();

        $reviews = ProductReview::with(["product", "customer"])
            ->where("is_approved", true)
            ->latest()
            ->limit(6)
            ->get();

        $vouchers = Voucher::where("is_active", true)
            ->where("valid_from", "<=", now())
            ->where("valid_until", ">=", now())
            ->where("usage_count", "<", DB::raw("usage_limit"))
            ->latest()
            ->limit(3)
            ->get();

        $promotions = Promotion::valid()
            ->with('items.product')
            ->latest()
            ->limit(6)
            ->get();

        $stats = [
            "products" => Product::active()->count(),
            "customers" => Customer::where("is_active", true)->count(),
            "rating" =>
                ProductReview::where("is_approved", true)->avg("rating") ?? 4.9,
        ];

        return view(
            "livewire.customer.home",
            compact(
                "categories",
                "featuredProducts",
                "heroProducts",
                "newProducts",
                "reviews",
                "vouchers",
                "promotions",
                "stats",
            ),
        );
    }
}
