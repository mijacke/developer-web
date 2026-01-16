<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DmFont extends Model
{
    protected $table = 'dm_fonts';

    protected $fillable = ['key', 'value'];

    public static function getSelected(): ?string
    {
        $active = static::where('key', 'active_font')->first();
        return $active ? $active->value : 'Inter';
    }

    public static function setSelected(string $fontName): void
    {
        static::updateOrCreate(
            ['key' => 'active_font'],
            ['value' => $fontName]
        );
    }

    public static function getAll(): array
    {
        return static::where('key', '!=', 'active_font')->get()->map(fn($f) => [
            'id' => $f->key,
            'name' => $f->key,
            'value' => $f->value,
        ])->toArray();
    }
}
