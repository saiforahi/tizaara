<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyBusinessType extends Model
{
    protected $fillable=['user_id','company_id','business_type_id'];
    protected $hidden=['created_at','updated_at'];

}
