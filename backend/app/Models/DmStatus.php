<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DmStatus extends Model
{
    protected $table = 'statuses_table';

    protected $fillable = ['name', 'label', 'color', 'is_default', 'sort_order'];

    protected $casts = [
        'is_default' => 'boolean',
    ];
}
