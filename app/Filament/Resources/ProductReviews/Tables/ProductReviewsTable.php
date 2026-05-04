<?php

namespace App\Filament\Resources\ProductReviews\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rating')
                    ->label(__('filament.product_review.rating'))
                    ->formatStateUsing(fn ($state) => str_repeat('★', $state) . str_repeat('☆', 5 - $state))
                    ->color('warning')
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label(__('filament.product_review.product'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer.name')
                    ->label(__('filament.product_review.customer'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('order.order_number')
                    ->label(__('filament.product_review.order'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('review')
                    ->label(__('filament.product_review.comment'))
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_approved')
                    ->boolean()
                    ->label(__('filament.product_review.is_approved')),
                TextColumn::make('approved_at')
                    ->label(__('filament.product_review.approved_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('filament.common.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('product')
                    ->relationship('product', 'name')
                    ->label(__('filament.product_review.product')),
                SelectFilter::make('customer')
                    ->relationship('customer', 'name')
                    ->label(__('filament.product_review.customer')),
                SelectFilter::make('rating')
                    ->label(__('filament.product_review.rating'))
                    ->options([
                        5 => __('filament.product_review.stars_5'),
                        4 => __('filament.product_review.stars_4'),
                        3 => __('filament.product_review.stars_3'),
                        2 => __('filament.product_review.stars_2'),
                        1 => __('filament.product_review.stars_1'),
                    ]),
                TernaryFilter::make('is_approved')
                    ->label(__('filament.product_review.is_approved')),
            ])
            ->recordActions([
                EditAction::make()
                    ->label(__('filament.common.edit')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label(__('filament.common.delete')),
                ]),
            ]);
    }
}
