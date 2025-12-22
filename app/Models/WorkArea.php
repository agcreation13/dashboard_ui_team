<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkArea extends Model
{
    protected $table = 'work_areas';

    protected $fillable = [
        'work_area_name',
        'status',
        'addedby',
    ];
}

