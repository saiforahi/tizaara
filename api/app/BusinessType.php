<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessType extends Model
{
    public function companies(){
        return $this->belongsToMany(CompanyBasicInfo::class,'company_business_types','business_type_id','company_id')->withTimestamps();
    }
}
