<?php

namespace App\Filament\Resources\Addresses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AddressesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')
                    ->label(__('filament.customer.name'))
                    ->searchable(),
                TextColumn::make('label')
                    ->label(__('filament.address.label'))
                    ->searchable(),
                TextColumn::make('city')
                    ->label(__('filament.address.city'))
                    ->searchable(),
                TextColumn::make('district')
                    ->label('Kecamatan')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('state')
                    ->label(__('filament.address.state'))
                    ->searchable(),
                TextColumn::make('postal_code')
                    ->label(__('filament.address.postal_code'))
                    ->searchable(),
                TextColumn::make('country')
                    ->label(__('filament.address.country'))
                    ->searchable(),
                TextColumn::make('phone')
                    ->label(__('filament.address.phone'))
                    ->searchable(),
                IconColumn::make('is_default')
                    ->label(__('filament.address.is_default'))
                    ->boolean(),
                TextColumn::make('latitude')
                    ->label(__('filament.address.latitude'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('longitude')
                    ->label(__('filament.address.longitude'))
                    ->numeric()
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
                //
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
