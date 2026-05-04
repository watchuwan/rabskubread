<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use Filament\Widgets\ChartWidget;

class CustomerGrowthChart extends ChartWidget
{
    protected ?string $heading = "Customer Growth";
    protected int|string|array $columnSpan = "full";
    protected ?string $maxHeight = "300px";

    protected function getData(): array
    {
        $months = collect(range(0, 11))
            ->map(function ($month) {
                return now()->subMonths($month)->format("M Y");
            })
            ->reverse();

        $data = collect(range(0, 11))
            ->map(function ($month) {
                return Customer::whereMonth(
                    "created_at",
                    now()->subMonths($month)->month,
                )
                    ->whereYear("created_at", now()->subMonths($month)->year)
                    ->count();
            })
            ->reverse();

        return [
            "datasets" => [
                [
                    "label" => "New Customers",
                    "data" => $data->toArray(),
                    "borderColor" => "rgb(59, 130, 246)",
                    "backgroundColor" => "rgba(59, 130, 246, 0.1)",
                ],
            ],
            "labels" => $months->toArray(),
        ];
    }

    protected function getType(): string
    {
        return "line";
    }
}
