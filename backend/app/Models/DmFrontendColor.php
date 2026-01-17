<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DmFrontendColor extends Model
{
    protected $table = 'dm_frontend_colors';

    protected $fillable = ['name', 'value', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the currently selected/active color.
     */
    public static function getSelected(): ?self
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Set a color as active (deactivates all others).
     */
    public static function setActive(int $id): void
    {
        static::query()->update(['is_active' => false]);
        static::where('id', $id)->update(['is_active' => true]);
    }
}
