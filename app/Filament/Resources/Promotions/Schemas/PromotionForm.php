<?php

namespace App\Filament\Resources\Promotions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PromotionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Promosi')->schema([
                TextInput::make('name')
                    ->label('Nama Promosi')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, $set) => $set('slug', \Str::slug($state))),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true),

                Select::make('type')
                    ->label('Tipe')
                    ->options([
                        'bundle'  => 'Bundle — beli N produk dapat harga khusus',
                        'package' => 'Package — paket beberapa produk (misal: meeting package)',
                    ])
                    ->required()
                    ->live(),

                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(2)
                    ->columnSpanFull(),
            ])->columns(2),

            Section::make('Harga & Ketentuan')->schema([
                TextInput::make('price')
                    ->label('Harga Promo (Rp)')
                    ->numeric()
                    ->required()
                    ->prefix('Rp'),

                TextInput::make('min_quantity')
                    ->label('Min. Qty (untuk Bundle)')
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->helperText('Jumlah minimum produk yang harus dibeli untuk mendapat harga bundle'),

                DateTimePicker::make('valid_from')
                    ->label('Berlaku Dari')
                    ->nullable(),

                DateTimePicker::make('valid_until')
                    ->label('Berlaku Sampai')
                    ->nullable(),

                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true)
                    ->columnSpanFull(),
            ])->columns(2),

            Section::make('Produk dalam Promosi')
                ->description('Untuk Bundle: pilih 1 produk. Untuk Package: pilih beberapa produk beserta qty-nya.')
                ->schema([
                    Repeater::make('items')
                        ->relationship()
                        ->label('')
                        ->schema([
                            Select::make('product_id')
                                ->label('Produk')
                                ->relationship('product', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            TextInput::make('quantity')
                                ->label('Qty')
                                ->numeric()
                                ->default(1)
                                ->minValue(1)
                                ->required(),
                        ])
                        ->columns(2)
                        ->addActionLabel('Tambah Produk')
                        ->minItems(1),
                ]),
        ]);
    }
}
