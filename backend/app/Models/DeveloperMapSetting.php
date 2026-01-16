<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeveloperMapSetting extends Model
{
    protected $table = 'developer_map_settings';

    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value by key
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        if (!$setting) {
            return $default;
        }
        
        $decoded = json_decode($setting->value, true);
        return json_last_error() === JSON_ERROR_NONE ? $decoded : $setting->value;
    }

    /**
     * Set a setting value
     */
    public static function setValue(string $key, $value): self
    {
        $encodedValue = is_string($value) ? $value : json_encode($value);
        
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $encodedValue]
        );
    }

    /**
     * Remove a setting
     */
    public static function removeValue(string $key): bool
    {
        return static::where('key', $key)->delete() > 0;
    }

    /**
     * Get all settings as key-value array
     */
    public static function getAllAsArray(): array
    {
        $settings = static::all();
        $result = [];
        
        foreach ($settings as $setting) {
            $decoded = json_decode($setting->value, true);
            $result[$setting->key] = json_last_error() === JSON_ERROR_NONE ? $decoded : $setting->value;
        }
        
        return $result;
    }
}
