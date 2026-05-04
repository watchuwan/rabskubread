<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class RevenueByCategoryChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 1;

    public function getHeading(): string
    {
        return __('filament.charts.revenue_by_category_heading');
    }

    protected function getData(): array
    {
        $revenue = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(order_items.subtotal) as total'))
            ->groupBy('categories.name')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => __('filament.charts.revenue'),
                    'data' => $revenue->pluck('total')->toArray(),
                    'backgroundColor' => [
                        'rgba(249, 115, 22, 0.8)',   // Orange
                        'rgba(16, 185, 129, 0.8)',   // Green
                        'rgba(59, 130, 246, 0.8)',   // Blue
                        'rgba(139, 92, 246, 0.8)',   // Purple
                        'rgba(236, 72, 153, 0.8)',   // Pink
                    ],
                    'borderColor' => [
                        'rgba(249, 115, 22, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(139, 92, 246, 1)',
                        'rgba(236, 72, 153, 1)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $revenue->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'right',
                ],
            ],
        ];
    }
}
