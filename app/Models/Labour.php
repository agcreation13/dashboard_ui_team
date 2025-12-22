<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\LabourRole;

class Labour extends Model
{
    protected $table = 'labours';

    protected $fillable = [
        'labour_id',
        'name',
        'role',
        'email',
        'phoneno',
        'aadhar_no',
        'dailywage',    
        'status',
        'remark',
        'addedBy',
    ];

    // Relationship with LabourRole model
    public function labourRole()
    {
        return $this->belongsTo(LabourRole::class, 'role', 'id');
    }
}
