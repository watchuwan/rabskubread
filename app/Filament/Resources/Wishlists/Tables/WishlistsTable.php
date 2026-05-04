<?php

namespace App\Filament\Resources\Wishlists\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class WishlistsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')
                    ->label(__('filament.wishlist.customer'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label(__('filament.wishlist.product'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.price')
                    ->label(__('filament.common.price'))
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('filament.wishlist.added_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('customer')
                    ->relationship('customer', 'name')
                    ->label(__('filament.wishlist.customer')),
                SelectFilter::make('product')
                    ->relationship('product', 'name')
                    ->label(__('filament.wishlist.product')),
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
