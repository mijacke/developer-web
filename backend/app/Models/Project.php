<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'type',
        'image',
        'map_key',
        'sort_order',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the parent project (N:1 self-referencing relationship for hierarchy)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'parent_id');
    }

    /**
     * Get child projects (1:N self-referencing relationship for hierarchy)
     */
    public function children(): HasMany
    {
        return $this->hasMany(Project::class, 'parent_id')->orderBy('sort_order');
    }

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

    /**
     * Check if this is a root project (no parent)
     */
    public function getIsRootAttribute(): bool
    {
        return $this->parent_id === null;
    }

    /**
     * Get all descendants recursively
     */
    public function descendants(): HasMany
    {
        return $this->children()->with('descendants');
    }
}
