<?php

namespace App\Models;

use App\Models\Labour;
use App\Models\SiteDetail;
use App\Models\WorkkittyList;
use Illuminate\Database\Eloquent\Model;

class WorkKittyAssignment extends Model
{
    protected $table = 'work_kitty_assignments';
    protected $fillable = [
        'site_id',
        'work_kitty_id',
        'total_work_day',
        'start_date',
        'actual_date',
        'end_date',
        'cwdc',
        'incentive_value',
        'labour_id',
        'status',
        'other',
        'verified',
        'verified_by',
        'remark',
        'reason',
        'verified_date',
        'added_by',
        'divide_by',
    ];
        public function sitedetail()
    {
        return $this->belongsTo(SiteDetail::class, 'site_id', 'id');
    }

        public function labour()
    {
        return $this->belongsTo(Labour::class, 'labour_id', 'id');
    }
        public function workkitty()
    {
        return $this->belongsTo(WorkkittyList::class, 'work_kitty_id', 'id');
    }
}
