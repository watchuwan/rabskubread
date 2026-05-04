<?php

namespace App\Filament\Resources\Carts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CartsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')
                    ->label(__('filament.cart.customer'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('items_count')
                    ->label(__('filament.cart.items'))
                    ->counts('items')
                    ->badge(),
                TextColumn::make('total_amount')
                    ->label(__('filament.common.total'))
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('filament.common.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('filament.common.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('customer')
                    ->relationship('customer', 'name')
                    ->label(__('filament.cart.customer')),
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
