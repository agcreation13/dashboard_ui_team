<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SiteDetail;
use App\Models\SiteBilling;

class PaymentReceipt extends Model
{
    
        public function Sitebilling()
    {
        return $this->belongsTo(SiteBilling::class, 'site_bill_no', 'site_bill_no');
    }
        public function sitedetail()
    {
        return $this->belongsTo(SiteDetail::class, 'site_id', 'id');
    }

}
