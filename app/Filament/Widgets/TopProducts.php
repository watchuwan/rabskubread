<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class TopProducts extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $pollingInterval = '1h';

    public function getHeading(): string
    {
        return __('filament.charts.top_products_heading');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->withCount('orderItems')
                    ->orderBy('order_items_count', 'desc')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament.product.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label(__('filament.product.category'))
                    ->searchable(),
                TextColumn::make('price')
                    ->label(__('filament.product.price'))
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('stock')
                    ->label(__('filament.product.stock'))
                    ->numeric()
                    ->sortable()
                    ->color(fn ($state): string => $state > 0 ? 'success' : 'danger'),
                TextColumn::make('order_items_count')
                    ->label(__('filament.charts.sold_count'))
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label(__('filament.common.active'))
                    ->boolean(),
            ])
            ->paginated(false);
    }
}
