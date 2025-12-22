<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Labour;
use App\Models\Leadsheet;


class LeadsFollow extends Model
{
     public function leadsheet()
    {
        return $this->belongsTo(Leadsheet::class, 'leads_id', 'id');
    }
     public function labour()
    {
        return $this->belongsTo(Labour::class, 'leads_follow_By', 'id');
    }
     public function handledby()
    {
        return $this->belongsTo(Labour::class, 'leads_handled_by', 'id');
    }

}
