<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DmFrontendColor extends Model
{
    protected $table = 'dm_frontend_colors';

    protected $fillable = ['name', 'label', 'value', 'is_default', 'is_custom', 'sort_order'];

    protected $casts = [
        'is_default' => 'boolean',
        'is_custom' => 'boolean',
    ];

    public static function getSelected(): ?self
    {
        // Return the one that's currently active (custom or default)
        return static::where('is_default', true)->first();
    }
}
