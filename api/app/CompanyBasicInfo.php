<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyBasicInfo extends Model
{
    protected $fillable = [
        'user_id', 'address_type', 'reg_address_id', 'ope_address_id', 'name',
        'display_name', 'establishment_date', 'office_space', 'website', 'email',
        'phone', 'cell', 'fax', 'number_of_employee', 'ownership_type',
        'revenue', 'main_product', 'other_product', 'is_verified'
    ];
    protected $hidden=['updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function companyCertifications()
    {
        return $this->hasMany(CompanyCertificate::class,'company_id','id');
    }
    public function companyTradeInfo()
    {
        return $this->hasMany(CompanyTradeInfo::class,'company_id','id');
    }
    public function companyNearestPort()
    {
        return $this->hasMany(CompanyNearestPort::class,'company_id','id');
    }
    public function companyFactories()
    {
        return $this->hasMany(CompanyFactory::class,'company_id','id');
    }
    public function businessTypes(){
        return $this->belongsToMany(BusinessType::class,'company_business_types','company_id','business_type_id')->withTimestamps();
    }
}
