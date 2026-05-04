<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.user.account_information'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('filament.user.name'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label(__('filament.user.email'))
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label(__('filament.user.password'))
                            ->password()
                            ->revealable()
                            ->maxLength(255)
                            ->required(fn (string $context): bool => $context === 'create')
                            ->dehydrated(fn ($state): bool => filled($state))
                            ->dehydrateStateUsing(fn ($state): string => bcrypt($state))
                            ->hiddenOn('edit'),
                        Toggle::make('is_active')
                            ->label(__('filament.user.is_active'))
                            ->default(true)
                            ->helperText(__('filament.user.active_helper')),
                    ])
                    ->columns(2),

                Section::make(__('filament.user.roles_permissions'))
                    ->schema([
                        Select::make('roles')
                            ->label(__('filament.user.role'))
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->preload()
                            ->searchable()
                            ->columnSpanFull()
                            ->helperText(__('filament.user.role_helper')),
                    ]),

                Section::make(__('filament.user.system_information'))
                    ->schema([
                        DateTimePicker::make('email_verified_at')
                        ->default(now())
                            ->label(__('filament.user.email_verified_at')),
                        DateTimePicker::make('last_login_at')
                        ->default(now())
                            ->label(__('filament.user.last_login_at')),
                    ])
->columnSpanFull()
                    ->columns(2)
                    ->collapsed(),
            ]);
    }
}
