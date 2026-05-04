<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ContactMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Nama')->required()->maxLength(100),
            TextInput::make('email')->label('Email')->email()->required()->maxLength(100),
            TextInput::make('subject')->label('Subjek')->required()->maxLength(200)->columnSpanFull(),
            Textarea::make('message')->label('Pesan')->required()->rows(5)->columnSpanFull()->readOnly(),
            Select::make('status')
                ->label('Status')
                ->options([
                    'unread'  => 'Belum Dibaca',
                    'read'    => 'Sudah Dibaca',
                    'replied' => 'Sudah Dibalas',
                ])
                ->required(),
            Textarea::make('reply')->label('Balasan')->rows(4)->columnSpanFull(),
        ]);
    }
}
