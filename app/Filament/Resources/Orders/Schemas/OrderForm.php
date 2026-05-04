<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.order.information'))
                    ->schema([
                        TextInput::make('order_number')
                            ->label(__('filament.order.order_number'))
                            ->required()
                            ->disabled()
                            ->dehydrated(false),
                        Select::make('status')
                            ->label(__('filament.order.status'))
                            ->options([
                                'pending' => __('filament.order.status_pending'),
                                'processing' => __('filament.order.status_processing'),
                                'shipped' => __('filament.order.status_shipped'),
                                'completed' => __('filament.order.status_completed'),
                                'cancelled' => __('filament.order.status_cancelled'),
                                'refunded' => __('filament.order.status_refunded'),
                            ])
                            ->default('pending')
                            ->required()
                            ->native(false),
                        Select::make('customer_id')
                            ->label(__('filament.order.customer'))
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('address_id')
                            ->label(__('filament.order.shipping_address'))
                            ->relationship('address', 'label')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('voucher_id')
                            ->label(__('filament.order.voucher'))
                            ->relationship('voucher', 'code')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Textarea::make('notes')
                            ->label(__('filament.order.notes'))
                            ->columnSpanFull()
                            ->rows(2),
                    ])
                    ->columns(2),

                Section::make(__('filament.order.pricing'))
                    ->schema([
                        TextInput::make('subtotal')
                            ->label(__('filament.order.subtotal'))
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0),
                        TextInput::make('shipping_cost')
                            ->label(__('filament.order.shipping_cost'))
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->default(0),
                        TextInput::make('voucher_discount')
                            ->label(__('filament.order.discount'))
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->default(0),
                        TextInput::make('total_amount')
                            ->label(__('filament.order.total_amount'))
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0),
                    ])
                    ->columns(4),

                Section::make(__('filament.order.timeline'))
                    ->schema([
                        DateTimePicker::make('paid_at')
                            ->label(__('filament.order.paid_at')),
                        DateTimePicker::make('shipped_at')
                            ->label(__('filament.order.shipped_at')),
                        DateTimePicker::make('completed_at')
                            ->label(__('filament.order.completed_at')),
                        DateTimePicker::make('cancelled_at')
                            ->label(__('filament.order.cancelled_at')),
                    ])
                    ->columns(4),
            ]);
    }
}
