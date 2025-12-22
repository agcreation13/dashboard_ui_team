<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkProcessList extends Model
{
    protected $fillable = [
        'site_id',
        'room_id',
        'room_sr_no',
        'room_work_entity',
        'total_value',
        'labour_percentage',
        'labour_value',
        'work_day_count',
        'work_area_code',
        'addedby',
        'status',
    ];
}
