<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('product_images')
                    ->collection('product_images')
                    ->conversion('thumb')
                    ->size(50)
                    ->circular()
                    ->label(__('filament.product.product_images')),
                TextColumn::make('category.name')
                    ->label(__('filament.product.category'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label(__('filament.product.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price')
                    ->label(__('filament.product.price'))
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('stock')
                    ->label(__('filament.product.stock'))
                    ->numeric()
                    ->sortable()
                    ->color(fn ($state): string => $state > 0 ? 'success' : 'danger'),
                TextColumn::make('sku')
                    ->label(__('filament.product.sku'))
                    ->searchable()
                    ->copyable(),
                TextColumn::make('rating_average')
                    ->label(__('filament.product.rating_average'))
                    ->numeric()
                    ->sortable()
                    ->icon('heroicon-o-star')
                    ->color('warning'),
                TextColumn::make('reviews_count')
                    ->label('Reviews')
                    ->counts('reviews')
                    ->badge()
                    ->color('info')
                    ->toggleable(),
                IconColumn::make('is_active')
                    ->label(__('filament.common.active'))
                    ->boolean(),
                IconColumn::make('in_stock')
                    ->label(__('filament.product.in_stock'))
                    ->boolean()
                    ->getStateUsing(fn ($record): bool => $record->stock > 0),
                TextColumn::make('created_at')
                    ->label(__('filament.common.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->label(__('filament.filters.category')),
                SelectFilter::make('status')
                    ->label(__('filament.filters.status'))
                    ->options([
                        'in_stock' => __('filament.filters.in_stock'),
                        'out_of_stock' => __('filament.filters.out_of_stock'),
                        'low_stock' => __('filament.filters.low_stock'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return match ($data['value']) {
                            'in_stock' => $query->where('stock', '>', 0),
                            'out_of_stock' => $query->where('stock', 0),
                            'low_stock' => $query->where('stock', '>', 0)->whereColumn('stock', '<=', 'low_stock_threshold'),
                            default => $query,
                        };
                    }),
                TernaryFilter::make('is_active')
                    ->label(__('filament.filters.is_active')),
            ])
            ->recordActions([
                EditAction::make()
                    ->label(__('filament.actions.edit')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label(__('filament.actions.bulk_delete')),
                ]),
            ]);
    }
}
