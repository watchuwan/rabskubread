<?php

namespace App\Filament\Resources\Payments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('payment_number')
                    ->label(__('filament.payment.payment_number'))
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),
                TextColumn::make('order.order_number')
                    ->label(__('filament.payment.order'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label(__('filament.payment.amount'))
                    ->money('IDR')
                    ->sortable(),
                BadgeColumn::make('status')
                    ->label(__('filament.payment.status'))
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'success',
                        'danger' => 'failed',
                        'danger' => 'expired',
                        'info' => 'refunded',
                    ])
                    ->formatStateUsing(fn ($state): string => match ($state) {
                        'pending' => __('filament.payment.status_pending'),
                        'success' => __('filament.payment.status_success'),
                        'failed' => __('filament.payment.status_failed'),
                        'expired' => __('filament.payment.status_expired'),
                        'refunded' => __('filament.payment.status_refunded'),
                        default => $state,
                    }),
                TextColumn::make('paid_at')
                    ->label(__('filament.payment.paid_at'))
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
                SelectFilter::make('status')
                    ->label(__('filament.payment.status'))
                    ->options([
                        'pending' => __('filament.payment.status_pending'),
                        'success' => __('filament.payment.status_success'),
                        'failed' => __('filament.payment.status_failed'),
                        'expired' => __('filament.payment.status_expired'),
                        'refunded' => __('filament.payment.status_refunded'),
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
