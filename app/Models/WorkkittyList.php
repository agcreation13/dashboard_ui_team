<?php

namespace App\Models;

use App\Models\WorkKittyAssignment;
use Illuminate\Database\Eloquent\Model;

class WorkkittyList extends Model
{

    protected $table = 'workkitty_lists';
    protected $fillable = [
        'site_id',
        'room_id',
        'workkitty_title',
        'workkitty_details',
        'work_proess_details',
        'workkitty_value',
        'status',
        'addedby',
        'created_by',
        'updated_by',
    ]; 
    //  protected $casts = [
    //     'workkitty_details' => 'array',  // Automatically cast JSON string to array
    // ];
     public function sitedetail()
    {
        return $this->belongsTo(SiteDetail::class, 'site_id', 'id');
    }
    
    public function workkittyAssignments()
    {
        return $this->hasMany(WorkKittyAssignment::class, 'work_kitty_id', 'id');
    }
}
