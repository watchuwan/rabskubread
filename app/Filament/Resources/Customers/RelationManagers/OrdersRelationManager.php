<?php

namespace App\Filament\Resources\Customers\RelationManagers;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\ShippingMethod;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_number')
                    ->label('Nomor Order')
                    ->required()
                    ->maxLength(255)
                    ->default(fn () => 'ORD-' . strtoupper(uniqid())),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending'    => 'Pending',
                        'processing' => 'Processing',
                        'shipped'    => 'Shipped',
                        'completed'  => 'Completed',
                        'cancelled'  => 'Cancelled',
                    ])
                    ->required()
                    ->default('processing'),

                Select::make('address_id')
                    ->label('Alamat Pengiriman')
                    ->options(fn () => $this->getOwnerRecord()
                        ->addresses()
                        ->get()
                        ->pluck('label', 'id'))
                    ->required(),

                Select::make('shipping_method_id')
                    ->label('Metode Pengiriman')
                    ->options(fn () => ShippingMethod::where('is_active', true)->pluck('name', 'id'))
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $method = ShippingMethod::find($state);
                            $set('shipping_cost', $method?->cost ?? 0);
                        }
                    }),

                Select::make('voucher_id')
                    ->label('Voucher')
                    ->relationship('voucher', 'code')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Textarea::make('notes')
                    ->label('Catatan (dari WA)')
                    ->rows(2)
                    ->columnSpanFull(),

                TextInput::make('subtotal')
                    ->label('Subtotal')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0),

                TextInput::make('shipping_cost')
                    ->label('Biaya Pengiriman')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0),

                TextInput::make('voucher_discount')
                    ->label('Diskon Voucher')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0),

                TextInput::make('total_amount')
                    ->label('Total')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('order_number')
            ->columns([
                TextColumn::make('order_number')
                    ->label('Nomor Order')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'pending'    => 'warning',
                        'processing' => 'info',
                        'shipped'    => 'primary',
                        'completed'  => 'success',
                        'cancelled'  => 'danger',
                        default      => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('items_count')
                    ->label('Items')
                    ->counts('items'),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Buat Order Manual')
                    ->successRedirectUrl(fn ($record) => OrderResource::getUrl('edit', ['record' => $record])),
            ])
            ->recordActions([
                EditAction::make()
                    ->url(fn ($record) => OrderResource::getUrl('edit', ['record' => $record])),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

