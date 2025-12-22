<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
     protected $table = 'materials';

    protected $fillable = [
        'name',
        'unit_type',
        'price_byunit',    
        'status',
        'remark',
        'addedBy',
    ];
}
