<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkProduct extends Model
{
    protected $table = 'work_products';

    protected $fillable = [
        'work_product_name',
        'rate',
        'labour_percentage',
        'working_p1_percentage',
        'working_p2_percentage',
        'status',
        'addedby',
    ];
}

