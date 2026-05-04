<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OrderStatusChart extends ChartWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 1;

    public function getHeading(): string
    {
        return __('filament.charts.order_status_heading');
    }

    protected function getData(): array
    {
        $statuses = Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        $statusLabels = [
            'pending' => __('filament.order_status.pending'),
            'processing' => __('filament.order_status.processing'),
            'completed' => __('filament.order_status.completed'),
            'cancelled' => __('filament.order_status.cancelled'),
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pesanan',
                    'data' => $statuses->pluck('count')->toArray(),
                    'backgroundColor' => [
                        'rgba(245, 158, 11, 0.8)',  // Yellow - pending
                        'rgba(59, 130, 246, 0.8)',  // Blue - processing
                        'rgba(16, 185, 129, 0.8)',  // Green - completed
                        'rgba(239, 68, 68, 0.8)',   // Red - cancelled
                    ],
                    'borderColor' => [
                        'rgba(245, 158, 11, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(239, 68, 68, 1)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $statuses->pluck('status')->map(fn($status) => $statusLabels[$status] ?? $status)->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
