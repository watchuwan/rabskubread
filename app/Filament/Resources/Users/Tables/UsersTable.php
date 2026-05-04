<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament.user.name'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('email')
                    ->label(__('filament.user.email'))
                    ->searchable()
                    ->copyable(),
                TextColumn::make('roles.name')
                    ->label(__('filament.user.role'))
                    ->badge()
                    ->separator(', '),
                IconColumn::make('email_verified_at')
                    ->label(__('filament.user.email_verified'))
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
                    ->label(__('filament.user.email_verified')),
                SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->label(__('filament.user.role')),
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
