<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Total Orders (Today vs Yesterday)
        $todayOrders = Order::whereDate('created_at', today())->count();
        $yesterdayOrders = Order::whereDate('created_at', today()->subDay())->count();
        $ordersChange = $yesterdayOrders > 0 ? round((($todayOrders - $yesterdayOrders) / $yesterdayOrders) * 100, 1) : 0;

        // Total Revenue (This Month vs Last Month)
        $thisMonthRevenue = Order::whereMonth('created_at', now()->month)->sum('total_amount');
        $lastMonthRevenue = Order::whereMonth('created_at', now()->subMonth()->month)->sum('total_amount');
        $revenueChange = $lastMonthRevenue > 0 ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) : 0;

        // Total Customers
        $totalCustomers = Customer::count();
        $newCustomersThisWeek = Customer::whereDate('created_at', '>=', now()->subWeek())->count();

        // Total Products
        $totalProducts = Product::count();
        $lowStockProducts = Product::whereColumn('stock', '<=', 'low_stock_threshold')->count();

        return [
            Stat::make(__('filament.stats.orders_today'), number_format($todayOrders))
                ->description($ordersChange > 0 ? "+{$ordersChange}% " . __('filament.stats.from_yesterday') : "{$ordersChange}% " . __('filament.stats.from_yesterday'))
                ->descriptionIcon($ordersChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->chart($this->generateRandomData())
                ->color($ordersChange >= 0 ? 'success' : 'danger'),

            Stat::make(__('filament.stats.revenue_this_month'), 'Rp ' . number_format($thisMonthRevenue, 0, ',', '.'))
                ->description($revenueChange > 0 ? "+{$revenueChange}% " . __('filament.stats.from_last_month') : "{$revenueChange}% " . __('filament.stats.from_last_month'))
                ->descriptionIcon($revenueChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->chart($this->generateRandomData())
                ->color($revenueChange >= 0 ? 'success' : 'danger'),

            Stat::make(__('filament.stats.total_customers'), number_format($totalCustomers))
                ->description("+{$newCustomersThisWeek} " . __('filament.stats.this_week'))
                ->descriptionIcon('heroicon-m-user-plus')
                ->chart($this->generateRandomData())
                ->color('info'),

            Stat::make(__('filament.stats.total_products'), number_format($totalProducts))
                ->description($lowStockProducts . ' ' . __('filament.stats.low_stock'))
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->chart($this->generateRandomData())
                ->color($lowStockProducts > 0 ? 'warning' : 'success'),
        ];
    }

    private function generateRandomData(): array
    {
        // Generate random data for sparkline charts
        return [
            rand(50, 100),
            rand(50, 100),
            rand(50, 100),
            rand(50, 100),
            rand(50, 100),
            rand(50, 100),
            rand(50, 100),
        ];
    }
}
