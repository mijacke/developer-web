<?php

namespace App\Traits;

use App\Models\AuditLog;

/**
 * Trait Auditable
 *
 * Automatically logs create, update, and delete events for models.
 * Add `use Auditable;` to any model that should be tracked.
 */
trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(function ($model) {
            AuditLog::log('create', $model, null, $model->getAttributes());
        });

        static::updated(function ($model) {
            $oldValues = array_intersect_key(
                $model->getOriginal(),
                $model->getDirty()
            );
            $newValues = $model->getDirty();

            if (!empty($newValues)) {
                AuditLog::log('update', $model, $oldValues, $newValues);
            }
        });

        static::deleted(function ($model) {
            AuditLog::log('delete', $model, $model->getOriginal(), null);
        });
    }
}
