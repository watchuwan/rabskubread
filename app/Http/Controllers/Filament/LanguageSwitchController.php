<?php

namespace App\Http\Controllers\Filament;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageSwitchController
{
    public function switch(string $locale): RedirectResponse
    {
        $supportedLocales = ['id', 'en'];

        if (!in_array($locale, $supportedLocales)) {
            $locale = 'id';
        }

        // Set locale in session
        Session::put('locale', $locale);
        
        // Set app locale for current request
        App::setLocale($locale);
        
        // Force session save
        Session::save();

        // Redirect with explicit locale parameter to force refresh
        return redirect()->to('/admin');
    }
}
