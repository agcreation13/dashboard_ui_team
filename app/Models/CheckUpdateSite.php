<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckUpdateSite extends Model
{
       protected $fillable = [
        'check_date',
        'check_site',
        'check_by',
        'check_status',
        'check_type',
        'check_run_types',
    ];
}
