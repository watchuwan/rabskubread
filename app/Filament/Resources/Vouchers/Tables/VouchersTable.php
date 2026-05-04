<?php

namespace App\Filament\Resources\Vouchers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VouchersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label(__('filament.voucher.code'))
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),
                TextColumn::make('name')
                    ->label(__('filament.voucher.name'))
                    ->searchable(),
                BadgeColumn::make('type')
                    ->label(__('filament.voucher.type'))
                    ->colors([
                        'primary' => 'percentage',
                        'success' => 'fixed',
                    ])
                    ->formatStateUsing(fn ($state): string => match ($state) {
                        'percentage' => __('filament.voucher.type_percentage'),
                        'fixed' => __('filament.voucher.type_fixed'),
                        default => $state,
                    }),
                TextColumn::make('value')
                    ->label(__('filament.voucher.value'))
                    ->formatStateUsing(fn ($record, $state): string => $record->type === 'percentage' ? "{$state}%" : 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),
                TextColumn::make('min_order_amount')
                    ->label(__('filament.voucher.min_purchase'))
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('usage_count')
                    ->label(__('filament.voucher.used_count'))
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn ($record, $state): string => $record->usage_limit ? "{$state}/{$record->usage_limit}" : "{$state}/∞"),
                TextColumn::make('valid_until')
                    ->label(__('filament.voucher.expires_at'))
                    ->date()
                    ->sortable()
                    ->color(fn ($record): string => $record->valid_until < now() ? 'danger' : 'success'),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label(__('filament.common.active')),
                TextColumn::make('created_at')
                    ->label(__('filament.common.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label(__('filament.voucher.type'))
                    ->options([
                        'percentage' => __('filament.voucher.type_percentage'),
                        'fixed' => __('filament.voucher.type_fixed'),
                    ]),
                TernaryFilter::make('is_active')
                    ->label(__('filament.common.active')),
                SelectFilter::make('status')
                    ->label(__('filament.common.status'))
                    ->options([
                        'valid' => __('filament.voucher.status_valid'),
                        'expired' => __('filament.voucher.status_expired'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return match ($data['value']) {
                            'valid' => $query->where('valid_until', '>=', now()),
                            'expired' => $query->where('valid_until', '<', now()),
                            default => $query,
                        };
                    }),
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
