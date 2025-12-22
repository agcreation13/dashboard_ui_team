<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Labour;
use App\Models\SiteDetail;

class RoomList extends Model
{
    protected $table = 'room_lists';

    // Mass assignable attributes
    protected $fillable = [
        'site_id',
        'room_qty',
        'room_details',
        'addedby',
        'status',
    ];

    // Cast room_details to array (JSON) automatically
    protected $casts = [
        'room_details' => 'array',
    ];

    public function sitedetail()
    {
        return $this->belongsTo(SiteDetail::class, 'site_id', 'id');
    }
    public function labour()
    {
        return $this->belongsTo(Labour::class, 'addedby', 'id');
    }
}
