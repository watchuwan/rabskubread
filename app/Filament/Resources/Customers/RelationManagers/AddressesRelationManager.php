<?php

namespace App\Filament\Resources\Customers\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'addresses';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('label')
                    ->label('Label Alamat')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Rumah, Kantor, dll'),
                TextInput::make('street_address')
                    ->label('Alamat Lengkap')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                TextInput::make('city')
                    ->label('Kota/Kabupaten')
                    ->required()
                    ->maxLength(100),
                TextInput::make('district')
                    ->label('Kecamatan')
                    ->maxLength(100),
                TextInput::make('state')
                    ->label('Provinsi')
                    ->required()
                    ->maxLength(100)
                    ->default('Maluku Utara'),
                TextInput::make('postal_code')
                    ->label('Kode Pos')
                    ->required()
                    ->maxLength(10),
                TextInput::make('country')
                    ->label('Negara')
                    ->required()
                    ->maxLength(100)
                    ->default('Indonesia'),
                TextInput::make('phone')
                    ->label('Nomor Telepon')
                    ->tel()
                    ->maxLength(20),
                \Filament\Forms\Components\Toggle::make('is_default')
                    ->label('Jadikan Alamat Utama')
                    ->default(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->columns([
                TextColumn::make('label')
                    ->label('Label')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('street_address')
                    ->label('Alamat')
                    ->searchable()
                    ->limit(30),
                TextColumn::make('city')
                    ->label('Kota')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('district')
                    ->label('Kecamatan')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('postal_code')
                    ->label('Kode Pos')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Telepon')
                    ->searchable(),
                TextColumn::make('is_default')
                    ->label('Utama')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'gray')
                    ->formatStateUsing(fn ($state) => $state ? 'Ya' : 'Tidak'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
