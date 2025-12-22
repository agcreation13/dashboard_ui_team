<?php

namespace App\Models;
use App\Models\RouteURLList;
use Illuminate\Database\Eloquent\Model;

class RoleRoutePermission extends Model
{
    
    protected $fillable = [
        'role_name',
        'status',
        'url_ids',
    ];

    protected $casts = [
        'url_ids' => 'array', // Automatically cast to array
    ];
       public function urlTitle()
    {
        return $this->belongsTo(RouteURLList::class, 'url_ids');
    }
}
