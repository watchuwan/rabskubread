<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OrdersChart extends ChartWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function getHeading(): string
    {
        return __('filament.charts.orders_chart_heading');
    }

    protected function getData(): array
    {
        $orders = Order::select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_amount) as revenue'),
            ])
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dates = $orders->pluck('date')->map(fn($date) => \Carbon\Carbon::parse($date)->format('d M'))->toArray();
        $counts = $orders->pluck('count')->toArray();
        $revenues = $orders->pluck('revenue')->toArray();

        return [
            'datasets' => [
                [
                    'label' => __('filament.charts.orders_count'),
                    'data' => $counts,
                    'backgroundColor' => 'rgba(249, 115, 22, 0.2)',
                    'borderColor' => 'rgba(249, 115, 22, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => true,
                ],
                [
                    'label' => __('filament.charts.revenue'),
                    'data' => array_map(fn($rev) => round($rev / 1000, 0), $revenues),
                    'backgroundColor' => 'rgba(16, 185, 129, 0.2)',
                    'borderColor' => 'rgba(16, 185, 129, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => true,
                ],
            ],
            'labels' => $dates,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'color' => 'rgba(0, 0, 0, 0.05)',
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
        ];
    }
}
