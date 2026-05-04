<?php

namespace App\Filament\Resources\Addresses\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AddressForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.address.address_information'))
                    ->schema([
                        Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('label')
                            ->placeholder(__('filament.address.label'))
                            ->helperText(__('filament.address.label') . ' ' . __('filament.common.description')),
                        Toggle::make('is_default')
                            ->default(false)
                            ->helperText(__('filament.address.is_default')),
                    ])
                    ->columns(3),

                Section::make(__('filament.address.address_details'))
                    ->schema([
                        Textarea::make('street_address')
                            ->required()
                            ->columnSpanFull()
                            ->rows(2),
                        TextInput::make('city')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('district')
                            ->label('Kecamatan')
                            ->maxLength(100),
                        TextInput::make('state')
                            ->maxLength(100),
                        TextInput::make('postal_code')
                            ->required()
                            ->maxLength(20),
                        TextInput::make('country')
                            ->required()
                            ->default('Indonesia')
                            ->maxLength(100),
                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(20),
                    ])
                    ->columns(2),

                Section::make(__('filament.address.location_coordinates'))
                    ->schema([
                        \Fahiem\FilamentPinpoint\Pinpoint::make('location')
                            ->provider('leaflet')
                            ->defaultLocation(0.7893, 127.3791)
                            ->defaultZoom(13)
                            ->height(400)
                            ->latField('latitude')
                            ->lngField('longitude')
                            ->streetField('street_address')
                            ->districtField('district')
                            ->cityField('city')
                            ->postalCodeField('postal_code')
                            ->countryField('country')
                            ->draggable()
                            ->searchable()
                            ->columnSpanFull(),
                        TextInput::make('latitude')
                            ->numeric()
                            ->readOnly()
                            ->suffix('°'),
                        TextInput::make('longitude')
                            ->numeric()
                            ->readOnly()
                            ->suffix('°'),
                    ])
                    ->columns(2),
            ]);
    }
}
