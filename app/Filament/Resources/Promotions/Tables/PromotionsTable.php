<?php

namespace App\Filament\Resources\Promotions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PromotionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'bundle'  => 'warning',
                        'package' => 'info',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'bundle'  => 'Bundle',
                        'package' => 'Package',
                    }),

                TextColumn::make('price')
                    ->label('Harga Promo')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('min_quantity')
                    ->label('Min. Qty')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Produk')
                    ->badge(),

                TextColumn::make('valid_from')
                    ->label('Dari')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('valid_until')
                    ->label('Sampai')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipe')
                    ->options(['bundle' => 'Bundle', 'package' => 'Package']),
                TernaryFilter::make('is_active')->label('Aktif'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
