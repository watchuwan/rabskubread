<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.category.information'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('filament.category.name'))
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn($state, $set) => $set('slug', \Str::slug($state))),
                        TextInput::make('slug')
                            ->label(__('filament.category.slug'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Textarea::make('description')
                            ->label(__('filament.category.description'))
                            ->columnSpanFull()
                            ->rows(3),
                        SpatieMediaLibraryFileUpload::make('icon')
                            ->label(__('filament.category.image'))
                            ->collection('icon')
                            ->image()
                            ->imageEditor()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make(__('filament.category.organization'))
                    ->schema([
                        Select::make('parent_id')
                            ->relationship('parent', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->label(__('filament.category.parent')),
                        TextInput::make('sort_order')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->label(__('filament.category.sort_order')),
                        Toggle::make('is_active')
                            ->label(__('filament.category.is_active'))
                            ->default(true)
                            ->helperText(__('filament.category.active_helper')),
                    ])
                    ->columns(3),
            ]);
    }
}
