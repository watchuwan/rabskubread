<?php

use App\Http\Controllers\Customer\Auth\SocialiteController;
use App\Livewire\Customer\Address\AddressForm;
use App\Livewire\Customer\Address\AddressList;
use App\Livewire\Customer\Auth\LoginForm;
use App\Livewire\Customer\Auth\RegisterForm;
use App\Livewire\Customer\Cart\CartPage;
use App\Livewire\Customer\Home;
use App\Livewire\Customer\Order\Checkout;
use App\Livewire\Customer\Order\OrderDetail;
use App\Livewire\Customer\Order\OrderHistory;
use App\Livewire\Customer\Order\OrderPayment;
use App\Livewire\Customer\Product\ProductDetail;
use App\Livewire\Customer\Product\ProductList;
use App\Livewire\Customer\Profile\ChangePassword;
use App\Livewire\Customer\Profile\Profile;
use App\Livewire\Customer\Review\MyReviews;
use App\Livewire\Customer\Wishlist\WishlistPage;
use App\Livewire\Customer\Comparison\ComparisonPage;
use App\Livewire\Customer\Loyalty\LoyaltyPage;
use App\Livewire\Customer\Referral\ReferralPage;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
|
| These routes are for the customer-facing storefront.
| Guest can browse products and view reviews.
| Authentication required for cart, wishlist, and orders.
|
*/

// Home Page
Route::get("/", Home::class)->name("home");

// Static Pages (Guest & Auth)
Route::get("/about", [App\Http\Controllers\Customer\PageController::class, 'about'])->name("about");
Route::get("/contact", [App\Http\Controllers\Customer\PageController::class, 'contact'])->name("contact");
Route::get("/faq", [App\Http\Controllers\Customer\PageController::class, 'faq'])->name("faq");

// Product Routes (Guest & Auth)
Route::get("/products", ProductList::class)->name("products.index");
Route::get("/products/{slug}", ProductDetail::class)->name("products.show");

// Authentication Routes (Guest only)
Route::middleware("guest:customer")->group(function () {
    Route::get("/login", LoginForm::class)->name("login");
    Route::get("/register", RegisterForm::class)->name("register");
    Route::get("/forgot-password", \App\Livewire\Customer\Auth\ForgotPasswordForm::class)->name("password.request");
    Route::get("/reset-password/{token}", \App\Livewire\Customer\Auth\ResetPasswordForm::class)->name("password.reset");

    // Socialite Routes
    Route::get("/auth/{provider}/redirect", [
        SocialiteController::class,
        "redirect",
    ])->name("auth.redirect");
    Route::get("/auth/{provider}/callback", [
        SocialiteController::class,
        "callback",
    ])->name("auth.callback");
});

// Authenticated Customer Routes
Route::middleware("auth:customer")->group(function () {
    // Dashboard
    Route::get("/dashboard", \App\Livewire\Customer\Dashboard::class)->name("dashboard");

    // Logout
    Route::post("/logout", function () {
        auth()->guard("customer")->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route("home");
    })->name("logout");

    // Profile Management
    Route::get("/profile", Profile::class)->name("profile.index");
    Route::get("/profile/password", ChangePassword::class)->name(
        "profile.password",
    );
    Route::get("/profile/reviews", MyReviews::class)->name("profile.reviews");

    // Address Management
    Route::get("/addresses", AddressList::class)->name("addresses.index");
    Route::get("/addresses/create", AddressForm::class)->name(
        "addresses.create",
    );
    Route::get("/addresses/{address}/edit", AddressForm::class)->name(
        "addresses.edit",
    );

    // Cart Routes
    Route::get("/cart", CartPage::class)->name("cart.index");

    // Wishlist Routes
    Route::get("/wishlist", WishlistPage::class)->name("wishlist.index");

    // Checkout & Order Routes
    Route::get("/checkout", Checkout::class)->name("checkout");
    Route::get("/orders", OrderHistory::class)->name("orders.index");
    Route::get("/orders/{order}", OrderDetail::class)->name("orders.show");
    Route::get("/orders/{order}/payment", OrderPayment::class)->name(
        "orders.payment",
    );
    Route::get("/orders/{order}/invoice", [\App\Http\Controllers\Customer\InvoiceController::class, 'download'])->name("orders.invoice");
});
