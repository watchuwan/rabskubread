<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Setting extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('setting_image')
            ->singleFile()
            ->useDisk('public');
    }
    protected $fillable = [
        "key",
        "value",
        "type",
        "group",
        "label",
        "description",
    ];

    /** Get a setting value by key, with optional default. */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("setting:{$key}", function () use (
            $key,
            $default,
        ) {
            $setting = static::where("key", $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /** Set a setting value and clear its cache. */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(["key" => $key], ["value" => $value]);
        Cache::forget("setting:{$key}");
    }

    /** Clear all settings cache. */
    public static function clearCache(): void
    {
        Cache::flush();
    }

    protected static function booted(): void
    {
        static::saved(function ($s) {
            Cache::forget("setting:{$s->key}");
            Cache::forget("app_settings");
        });
        static::deleted(function ($s) {
            Cache::forget("setting:{$s->key}");
            Cache::forget("app_settings");
        });
    }
}
