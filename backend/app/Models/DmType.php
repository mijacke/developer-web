<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DmType extends Model
{
    protected $table = 'dm_types';

    protected $fillable = ['name', 'label', 'color', 'sort_order'];
}
