<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Material;
use App\Models\User;
use App\Models\SiteDetail;

class DailyMaterialEntry extends Model
{

      protected $fillable = [
        'site_id',
        'material_add_date',
        'material_data',
        'added_by',
    ];

    public function material(){
          return $this->belongsTo(Material::class, 'material_id');
      }
    public function user(){
          return $this->belongsTo(User::class, 'added_by');
      }
     public function sitedetails(){
          return $this->belongsTo(SiteDetail::class, 'site_id');
      }
}
