<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RevenueStats extends BaseWidget
{
    protected function getStats(): array
    {
        $today = Order::whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('total_amount');

        $thisMonth = Order::whereMonth('created_at', now()->month)
            ->where('status', 'completed')
            ->sum('total_amount');

        $lastMonth = Order::whereMonth('created_at', now()->subMonth()->month)
            ->where('status', 'completed')
            ->sum('total_amount');

        $growth = $lastMonth > 0 ? (($thisMonth - $lastMonth) / $lastMonth) * 100 : 0;

        return [
            Stat::make('Today Revenue', 'Rp ' . number_format($today, 0, ',', '.'))
                ->description('Revenue hari ini')
                ->color('success'),

            Stat::make('This Month', 'Rp ' . number_format($thisMonth, 0, ',', '.'))
                ->description(number_format($growth, 1) . '% vs last month')
                ->color($growth >= 0 ? 'success' : 'danger'),

            Stat::make('Avg Order Value', 'Rp ' . number_format($thisMonth / max(Order::whereMonth('created_at', now()->month)->count(), 1), 0, ',', '.'))
                ->description('Average per order')
                ->color('info'),
        ];
    }
}
