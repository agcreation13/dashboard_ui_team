<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabourRole extends Model
{
    protected $table = 'labour_roles'; // Specify the table name if different
    protected $fillable = ['name','parent_id','addedBy']; 
}
