<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'map_key',
        'settings',
        'is_active',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get all localities for the project (1:N relationship)
     */
    public function localities(): HasMany
    {
        return $this->hasMany(Locality::class)->orderBy('sort_order');
    }

    /**
     * Get count of available localities
     */
    public function getAvailableCountAttribute(): int
    {
        return $this->localities()->where('status', 'available')->count();
    }
}
