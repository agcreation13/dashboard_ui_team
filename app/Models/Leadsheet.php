<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Labour;
use App\Models\LeadsFollow;

class Leadsheet extends Model
{
     public function labour()
    {
        return $this->belongsTo(Labour::class, 'leads_handled_by', 'id');
    }
     // In Leadsheet.php
    public function leadsfollows()
    {
        return $this->hasMany(LeadsFollow::class, 'leads_id', 'id');
    }
    
    // Optional: one "open" follow-up
    public function openFollow()
    {
        return $this->hasOne(LeadsFollow::class, 'leads_id', 'id')->where('leads_follow_status', 'open')->latest();
    }

public function leadFollows()
{
    return $this->hasMany(LeadsFollow::class, 'leads_id');
}

public function latestFollow()
{
    return $this->hasOne(LeadsFollow::class, 'leads_id')->latestOfMany();
}
}
