<?php

namespace App\Filament\Resources\Customers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                    ->label(__('filament.customer.avatar'))
                    ->disk('public')
                    ->size(40)
                    ->circular(),
                TextColumn::make('name')
                    ->label(__('filament.customer.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('filament.customer.email'))
                    ->searchable()
                    ->copyable(),
                TextColumn::make('phone')
                    ->label(__('filament.customer.phone'))
                    ->searchable(),
                TextColumn::make('orders_count')
                    ->label('Total Orders')
                    ->counts('orders')
                    ->sortable()
                    ->badge()
                    ->color('success'),
                TextColumn::make('total_spent')
                    ->label('Total Spent')
                    ->money('IDR')
                    ->sortable(),
                IconColumn::make('email_verified_at')
                    ->label(__('filament.customer.email_verified'))
                    ->icon(fn ($record): string => $record->email_verified_at ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn ($record): string => $record->email_verified_at ? 'success' : 'gray'),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label(__('filament.common.active')),
                TextColumn::make('last_login_at')
                    ->label(__('filament.user.last_login_at'))
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
                TernaryFilter::make('is_active')
                    ->label(__('filament.common.active')),
                TernaryFilter::make('email_verified_at')
                    ->label(__('filament.customer.email_verified')),
                SelectFilter::make('gender')
                    ->label(__('filament.customer.gender'))
                    ->options([
                        'male' => __('filament.customer.gender_male'),
                        'female' => __('filament.customer.gender_female'),
                        'other' => __('filament.customer.gender_other'),
                    ]),
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
