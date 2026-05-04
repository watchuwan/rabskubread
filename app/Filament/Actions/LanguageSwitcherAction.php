<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageSwitcherAction
{
    public static function make(): array
    {
        $currentLocale = Session::get('locale', App::currentLocale());

        $languages = [
            'id' => ['label' => 'Indonesia', 'flag' => '🇮🇩'],
            'en' => ['label' => 'English',   'flag' => '🇬🇧'],
        ];

        return array_map(
            fn (string $locale, array $info) => Action::make("language_{$locale}")
                ->label("{$info['flag']} {$info['label']}")
                ->icon($currentLocale === $locale ? 'heroicon-s-check' : 'heroicon-o-language')
                ->url(url('/admin/language/switch/' . $locale)),
            array_keys($languages),
            $languages,
        );
    }
}
