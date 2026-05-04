<?php

namespace App\Filament\Resources\Vouchers\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VoucherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.voucher.information'))
                    ->schema([
                        TextInput::make('code')
                            ->label(__('filament.voucher.code'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->columnSpanFull(),
                        TextInput::make('name')
                            ->label(__('filament.voucher.name'))
                            ->required()
                            ->maxLength(255),
                        Select::make('type')
                            ->label(__('filament.voucher.type'))
                            ->options([
                                'percentage' => __('filament.voucher.type_percentage'),
                                'fixed' => __('filament.voucher.type_fixed'),
                            ])
                            ->default('fixed')
                            ->required()
                            ->live()
                            ->native(false),
                        TextInput::make('value')
                            ->label(__('filament.voucher.value'))
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->prefix(fn (callable $get): string => $get('type') === 'percentage' ? '%' : 'Rp')
                            ->helperText(fn (callable $get): string => $get('type') === 'percentage' ? __('filament.voucher.value_percentage_helper') : __('filament.voucher.value_fixed_helper')),
                        Textarea::make('description')
                            ->label(__('filament.voucher.description'))
                            ->columnSpanFull()
                            ->rows(2),
                    ])
                    ->columnSpanFull()
                    ->columns(2),

                Section::make(__('filament.voucher.usage_conditions'))
                    ->schema([
                        TextInput::make('min_order_amount')
                            ->label(__('filament.voucher.min_purchase'))
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->prefix('Rp')
                            ->helperText(__('filament.voucher.min_order_amount_helper')),
                        TextInput::make('max_discount')
                            ->label(__('filament.voucher.max_discount'))
                            ->numeric()
                            ->minValue(0)
                            ->prefix('Rp')
                            ->helperText(__('filament.voucher.max_discount_helper')),
                        TextInput::make('usage_limit')
                            ->label(__('filament.voucher.usage_limit'))
                            ->numeric()
                            ->minValue(0)
                            ->helperText(__('filament.voucher.usage_limit_helper')),
                        TextInput::make('usage_count')
                            ->label(__('filament.voucher.used_count'))
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->disabled()
                            ->dehydrated(false),
                    ])
                        ->columnSpanFull()
                    ->columns(2),

                Section::make(__('filament.voucher.validity_period'))
                    ->schema([
                        DateTimePicker::make('valid_from')
                            ->label(__('filament.voucher.starts_at'))
                            ->required()
                            ->native(false),
                        DateTimePicker::make('valid_until')
                            ->label(__('filament.voucher.expires_at'))
                            ->required()
                            ->native(false),
                        Toggle::make('is_active')
                            ->label(__('filament.voucher.is_active'))
                            ->default(true)
                            ->helperText(__('filament.voucher.active_helper')),
                    ])
                        ->columnSpanFull()
                    ->columns(3),
            ]);
    }
}
