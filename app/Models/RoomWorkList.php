<?php

namespace App\Models;

use App\Models\Labour;
use App\Models\RoomList;
use App\Models\SiteDetail;
use Illuminate\Database\Eloquent\Model;

class RoomWorkList extends Model
{
    protected $fillable = [
        'site_id',
        'room_id',
        'room_sr_no',
        'work_area_name',
        'work_product_name',
        'unit',
        'work_area_quantity',
        'rate',
        'total_value',
        'day_count',
        'workplan_percentage',
        'workplan_percentage_value',
        'workplan_description',
        'work_area_code',
        'addedby',
        'status',
    ];

    public function sitedetail()
    {
        return $this->belongsTo(SiteDetail::class, 'site_id', 'id');
    }
    
    public function labour()
    {
        return $this->belongsTo(Labour::class, 'addedby', 'id');
    }
  
    public function roomname($id, $room_id)
    {
        $roomList = RoomList::find($id);  // This fetches the RoomList by its ID
        if ($roomList) {
            $roomDetails = is_array($roomList->room_details) ? $roomList->room_details : json_decode($roomList->room_details, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return 'Invalid JSON';  // Handle invalid JSON format
            }
            $room = collect($roomDetails)->firstWhere('id', (string) $room_id);  // Match the room_id with the id in room_details
            return $room ? $room['name'] : 'Room not found';
        }

        return 'Room not found';  // If no RoomList is found
    }
}
