<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DmFrontendColor extends Model
{
    protected $table = 'frontend_colors_table';

    protected $fillable = ['name', 'value', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function getSelected(): ?self
    {
        return static::where('is_active', true)->first();
    }

    public static function setActive(int $id): void
    {
        static::query()->update(['is_active' => false]);
        static::where('id', $id)->update(['is_active' => true]);
    }
}
