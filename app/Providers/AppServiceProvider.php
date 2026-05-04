<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\User;
use App\Http\Middleware\CustomerActivity;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share settings to all views
        View::share('settings', \App\Models\Setting::all()->pluck('value', 'key'));

        // Add persistent middleware for Livewire
        Livewire::addPersistentMiddleware([
            CustomerActivity::class,
        ]);

        // Define Gates for Order
        Gate::define('manage-orders', function (User $user) {
            return $user->hasRole(['super_admin', 'admin_staff']);
        });

        Gate::define('view-order', function (User $user, Order $order) {
            return $user->hasRole(['super_admin', 'admin_staff']) || $order->customer_id === $user->customer?->id;
        });

        Gate::define('update-order', function (User $user, Order $order) {
            return $user->hasRole(['super_admin', 'admin_staff']);
        });

        Gate::define('delete-order', function (User $user, Order $order) {
            return $user->hasRole(['super_admin']);
        });

        // Define Gates for Product
        Gate::define('manage-products', function (User $user) {
            return $user->hasRole(['super_admin', 'admin_staff']);
        });

        Gate::define('view-product', function (User $user, Product $product) {
            return true; // All authenticated users can view products
        });

        Gate::define('update-product', function (User $user, Product $product) {
            return $user->hasRole(['super_admin', 'admin_staff']);
        });

        Gate::define('delete-product', function (User $user, Product $product) {
            return $user->hasRole(['super_admin']);
        });

        // Define Gates for Customer
        Gate::define('manage-customers', function (User $user) {
            return $user->hasRole(['super_admin', 'admin_staff']);
        });

        Gate::define('view-customer', function (User $user, Customer $customer) {
            return $user->hasRole(['super_admin', 'admin_staff']) || $customer->id === $user->customer?->id;
        });

        Gate::define('update-customer', function (User $user, Customer $customer) {
            return $user->hasRole(['super_admin', 'admin_staff']) || $customer->id === $user->customer?->id;
        });

        Gate::define('delete-customer', function (User $user, Customer $customer) {
            return $user->hasRole(['super_admin']);
        });

        // Define Gates for User Management
        Gate::define('manage-users', function (User $user) {
            return $user->hasRole(['super_admin']);
        });

        Gate::define('view-user', function (User $user, User $targetUser) {
            return $user->hasRole(['super_admin', 'admin_staff']) || $user->id === $targetUser->id;
        });

        Gate::define('update-user', function (User $user, User $targetUser) {
            return $user->hasRole(['super_admin']) || ($user->hasRole('admin_staff') && $user->id === $targetUser->id);
        });

        Gate::define('delete-user', function (User $user, User $targetUser) {
            return $user->hasRole(['super_admin']) && $user->id !== $targetUser->id; // Cannot delete self
        });

        // Define Gates for Reports
        Gate::define('view-reports', function (User $user) {
            return $user->hasRole(['super_admin', 'admin_staff']);
        });

        // Define Gates for Settings
        Gate::define('manage-settings', function (User $user) {
            return $user->hasRole(['super_admin']);
        });
    }
}
