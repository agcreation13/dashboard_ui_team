<?php

namespace App\Models;
use App\Models\Labour;
use App\Models\User;
use App\Models\SiteDetail;

use Illuminate\Database\Eloquent\Model;

class DailyLabourEntry extends Model
{
    protected $fillable = [
        'site_id',
        'plan_of_date',
        'plan_message',
        'labour_data',
        'added_by',
        'daily_labour_status',
    ];
     
      public function labour(){
          return $this->belongsTo(Labour::class, 'labour_id');
      }
      public function user(){
          return $this->belongsTo(User::class, 'added_by');
      }
      public function sitedetails(){
          return $this->belongsTo(SiteDetail::class, 'site_id');
      }
    //   public function site(){
    //       return $this->belongsTo(SiteDetail::class, 'site_id');
    //   }

    
      
}
