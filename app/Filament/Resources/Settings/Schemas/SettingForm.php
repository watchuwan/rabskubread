<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make("key")
                ->label("Key")
                ->required()
                ->unique(ignoreRecord: true)
                ->alphaDash()
                ->maxLength(100),

            TextInput::make("label")
                ->label("Label")
                ->required()
                ->maxLength(100),

            Select::make("group")
                ->label("Grup")
                ->options([
                    "general" => "General",
                    "contact" => "Contact",
                    "social" => "Social",
                    "seo" => "SEO",
                ])
                ->required(),

            Select::make("type")
                ->label("Tipe")
                ->options([
                    "text" => "Text",
                    "textarea" => "Textarea",
                    "boolean" => "Boolean",
                    "image" => "Image",
                    "url" => "URL",
                ])
                ->required(),

            Textarea::make("value")
                ->label("Nilai")
                ->rows(3)
                ->hidden(fn($get) => $get("type") === "image"),

            SpatieMediaLibraryFileUpload::make("image")
                ->label("Gambar")
                ->collection("setting_image")
                ->image()
                ->imagePreviewHeight("150")
                ->nullable()
                ->visible(fn($get) => $get("type") === "image"),

            Textarea::make("description")
                ->label("Deskripsi")
                ->rows(2)
                ->nullable(),
        ]);
    }
}
