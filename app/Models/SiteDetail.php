<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\LabourRole;
use App\Models\Labour;
use App\Models\SiteDetail;
use App\Models\DailyLabourEntry;
use App\Models\DailyMaterialEntry;
use App\Models\SiteBilling;
use App\Models\PaymentReceipt;

class SiteDetail extends Model
{
        
     protected $table = 'site_details';

    protected $fillable = [
            'name',               
            'email',                
            'phone_no',                
            'site_type',                
            'owner_name',               
            'designer_name',               
            'designer_phone_no',               
            'address',               
            'start_date',               
            'project_id',               
            'representative',               
            'supervisor',               
            'mukadam',               
            'status',               
            'bill_value',               
            'payment_received',               
            'next_payment_date',               
            'standby',                
            'standby_reason',                
            'close_reason',                
            'addedBy',                
            ];   

      public function labour_r(){
          return $this->belongsTo(Labour::class, 'representative');
      }
      
      public function labour_s(){
          return $this->belongsTo(Labour::class, 'supervisor');
      }
    
      public function labour_m(){
          return $this->belongsTo(Labour::class, 'mukadam');
      }
    
      public function dailyLabourEntries(){
           return $this->hasMany(DailyLabourEntry::class, 'site_id');
       }

      public function dailyMaterialEntries(){
           return $this->hasMany(DailyMaterialEntry::class, 'site_id');
       }
       public function billings(){
         return $this->hasMany(SiteBilling::class, 'site_id');
       }
       public function receipts(){
         return $this->hasMany(PaymentReceipt::class, 'site_id');
       }

       
}
