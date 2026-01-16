<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DmMapColor extends Model
{
    protected $table = 'dm_map_colors';

    protected $fillable = ['name', 'label', 'value', 'sort_order'];
}
