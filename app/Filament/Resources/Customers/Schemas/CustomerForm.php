<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.customer.account_information'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('filament.customer.name'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label(__('filament.customer.email'))
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label(__('filament.user.password'))
                            ->password()
                            ->revealable()
                            ->maxLength(255)
                            ->required(fn(string $context): bool => $context === 'create')
                            ->dehydrated(fn($state): bool => filled($state))
                            ->dehydrateStateUsing(fn($state): string => bcrypt($state))
                            ->hiddenOn('edit'),
                        TextInput::make('phone')
                            ->label(__('filament.customer.phone'))
                            ->tel()
                            ->maxLength(20),
                        FileUpload::make('avatar')
                            ->label(__('filament.customer.avatar'))
                            ->image()
                            ->disk('public')
                            ->directory('avatars')
                            ->visibility('public')
                            ->columnSpanFull()
                            ->maxSize(2048),
                    ])
                    ->columnSpanFull()
                    ->columns(2),

                Section::make(__('filament.customer.personal_details'))
                    ->schema([
                        DatePicker::make('birth_date')
                            ->label(__('filament.customer.birth_date'))
                            ->native(false),
                        Select::make('gender')
                            ->label(__('filament.customer.gender'))
                            ->options([
                                'male' => __('filament.customer.gender_male'),
                                'female' => __('filament.customer.gender_female'),
                                'other' => __('filament.customer.gender_other'),
                            ])
                            ->native(false),
                        Toggle::make('is_active')
                            ->label(__('filament.customer.is_active'))
                            ->default(true)
                            ->helperText(__('filament.customer.active_helper')),
                    ])
                    ->columnSpanFull()
                    ->columns(3),

                Section::make(__('filament.customer.system_information'))
                    ->schema([
                        DateTimePicker::make('email_verified_at')
                            ->label(__('filament.customer.email_verified_at')),
                        DateTimePicker::make('last_login_at')
                            ->label(__('filament.user.last_login_at')),
                        TextInput::make('google_id')
                            ->label(__('filament.socialite_user.google_id'))
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('facebook_id')
                            ->label(__('filament.socialite_user.facebook_id'))
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('oauth_provider')
                            ->label(__('filament.socialite_user.oauth_provider'))
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columnSpanFull()
                    ->columns(3)
                    ->collapsed(),
            ]);
    }
}
