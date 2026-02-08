<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DmMapColor extends Model
{
    protected $table = 'colors_table';

    protected $fillable = ['name', 'label', 'value', 'sort_order'];
}
