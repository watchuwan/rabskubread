<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\KeyValue;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label(__('filament.product.category')),
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->label(__('filament.product.name'))
                    ->afterStateUpdated(fn ($state, $set) => $set('slug', \Str::slug($state))),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->label(__('filament.product.slug')),
                Textarea::make('description')
                    ->label(__('filament.product.description'))
                    ->columnSpanFull()
                    ->rows(3),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->minValue(0)
                    ->label(__('filament.product.price')),
                TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->label(__('filament.product.stock')),
                TextInput::make('low_stock_threshold')
                    ->numeric()
                    ->minValue(0)
                    ->default(5)
                    ->label(__('filament.product.low_stock_threshold')),
                TextInput::make('sku')
                    ->label(__('filament.product.sku'))
                    ->unique(ignoreRecord: true),
                TagsInput::make('ingredients')
                    ->placeholder(__('filament.product.ingredients'))
                    ->columnSpanFull()
                    ->label(__('filament.product.ingredients')),
                TagsInput::make('allergens')
                    ->placeholder(__('filament.product.allergens'))
                    ->columnSpanFull()
                    ->label(__('filament.product.allergens')),
                TextInput::make('preparation_time')
                    ->numeric()
                    ->minValue(0)
                    ->suffix(__('filament.common.minutes'))
                    ->helperText(__('filament.product.preparation_time'))
                    ->label(__('filament.product.preparation_time')),
                Toggle::make('is_customizable')
                    ->helperText(__('filament.product.is_customizable'))
                    ->label(__('filament.product.is_customizable')),
                KeyValue::make('customization_options')
                    ->keyLabel(__('filament.product.option_name'))
                    ->valueLabel(__('filament.product.option_value'))
                    ->addActionLabel(__('filament.product.add_option'))
                    ->label(__('filament.product.customization_options')),
                TextInput::make('size')
                    ->placeholder(__('filament.product.size'))
                    ->label(__('filament.product.size')),
                TextInput::make('calories')
                    ->numeric()
                    ->minValue(0)
                    ->suffix('kcal')
                    ->label(__('filament.product.calories')),
                Toggle::make('is_active')
                    ->default(true)
                    ->helperText(__('filament.product.is_active'))
                    ->label(__('filament.common.active')),
                Select::make('shippingMethods')
                    ->relationship('shippingMethods', 'name')
                    ->multiple()
                    ->preload()
                    ->label('Metode Pengiriman')
                    ->helperText('Pilih metode pengiriman yang tersedia untuk produk ini'),
                SpatieMediaLibraryFileUpload::make('product_images')
                    ->collection('product_images')
                    ->multiple()
                    ->maxFiles(10)
                    ->minFiles(1)
                    ->image()
                    ->imageEditor()
                    ->responsiveImages()
                    ->conversion('thumb')
                    ->columnSpanFull()
                    ->label(__('filament.product.product_images')),
            ]);
    }
}
