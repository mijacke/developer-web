<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DmSetting extends Model
{
    protected $table = 'dm_settings';

    protected $fillable = ['key', 'value'];

    public static function getValue(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function setValue(string $key, $value): self
    {
        return static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
