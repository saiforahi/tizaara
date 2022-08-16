<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatConversationType extends Model
{
    protected $fillable=['messageable1_type','messageable1_id','messageable2_type','messageable2_id','message_type'];
    protected $hidden=['created_at','updated_at'];

    public function messageable1(){
        return $this->morphTo('messageable1');
    }
    public function messageable2(){
        return $this->morphTo('messageable2');
    }

    public function companyBasic()
    {
        return $this->belongsTo(CompanyBasicInfo::class,'messageable2_id','user_id');
    }

    public function companyDetails()
    {
        return $this->belongsTo(CompanyDetails::class,'messageable2_id','user_id');
    }
}
