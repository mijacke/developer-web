<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DmColor extends Model
{
    protected $table = 'dm_colors';

    protected $fillable = ['name', 'label', 'value', 'sort_order'];
}
