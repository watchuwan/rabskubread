<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Share settings to all views
        View::composer('*', function ($view) {
            $settings = cache()->remember('app_settings', 3600, function () {
                $all = Setting::pluck('value', 'key')->toArray();

                // Override image-type settings with Spatie media URL
                Setting::where('type', 'image')->get()->each(function ($setting) use (&$all) {
                    $media = $setting->getFirstMedia('setting_image');
                    $all[$setting->key] = $media ? $media->getUrl() : null;
                });

                return $all;
            });

            $view->with('settings', $settings);
        });
    }
}
