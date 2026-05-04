<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.payment.information'))
                    ->schema([
                        Select::make('order_id')
                            ->label(__('filament.payment.order'))
                            ->relationship('order', 'order_number')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('payment_number')
                            ->label(__('filament.payment.payment_number'))
                            ->required()
                            ->disabled()
                            ->dehydrated(false),
                        Select::make('status')
                            ->label(__('filament.payment.status'))
                            ->options([
                                'pending' => __('filament.payment.status_pending'),
                                'success' => __('filament.payment.status_success'),
                                'failed' => __('filament.payment.status_failed'),
                                'expired' => __('filament.payment.status_expired'),
                                'refunded' => __('filament.payment.status_refunded'),
                            ])
                            ->default('pending')
                            ->required()
                            ->native(false),
                    ])
                    ->columns(2),

                Section::make(__('filament.payment.details'))
                    ->schema([
                        TextInput::make('amount')
                            ->label(__('filament.payment.amount'))
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0),
                        DateTimePicker::make('paid_at')
                            ->label(__('filament.payment.paid_at')),
                        TextInput::make('midtrans_transaction_id')
                            ->label(__('filament.payment.transaction_id'))
                            ->disabled()
                            ->dehydrated(false),
                        Textarea::make('midtrans_response')
                            ->label(__('filament.payment.midtrans_response'))
                            ->columnSpanFull()
                            ->rows(3)
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2),
            ]);
    }
}
