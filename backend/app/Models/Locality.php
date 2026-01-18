<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Locality extends Model
{
    use Auditable;
    protected $fillable = [
        'project_id',
        'name',
        'type',
        'status',
        'status_label',
        'status_color',
        'area',
        'price',
        'rent',
        'floor',
        'image',
        'svg_path',
        'metadata',
        'sort_order',
    ];

    protected $casts = [
        'area' => 'decimal:2',
        'price' => 'decimal:2',
        'rent' => 'decimal:2',
        'metadata' => 'array',
        'sort_order' => 'integer',
    ];

    /**
     * Get the project that owns the locality (N:1 relationship)
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Check if locality is available
     */
    public function getIsAvailableAttribute(): bool
    {
        return in_array(strtolower($this->status), ['available', 'voľný', 'volny', 'predaj']);
    }
}
