<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group', 'label'];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        return Cache::remember('setting.' . $key, 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('setting.' . $key);
    }

    /**
     * Get all settings grouped
     */
    public static function getAllGrouped(): array
    {
        return Cache::remember('settings.all', 3600, function () {
            return static::all()->groupBy('group')->map(fn($group) => 
                $group->keyBy('key')->map(fn($item) => $item->value)
            )->toArray();
        });
    }

    /**
     * Clear settings cache
     */
    public static function clearCache(): void
    {
        // Clear individual setting cache
        Cache::forget('settings.all');
        $keys = static::pluck('key');
        foreach ($keys as $key) {
            Cache::forget('setting.' . $key);
        }
        
        // Clear View Composer cache
        Cache::forget('app_settings');
        Cache::forget('sidebar_settings');
    }
}
