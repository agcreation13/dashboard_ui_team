<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SiteDetail;

class SiteBilling extends Model
{    
    protected $table = 'site_billings'; // Specify the table name if different
    protected $fillable = ['site_id','site_bill_no','site_bill_date','site_bill_value','status','addedBy'];

      public function sitedetail()
    {
        return $this->belongsTo(SiteDetail::class, 'site_id', 'id');
    }
    
}
