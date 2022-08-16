<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyCertificate extends Model
{
    use SoftDeletes;

    protected $fillable=[
        'name', 'company_id', 'reference_number', 'issued_by', 'start_date', 'end_date',
        'certificate_photo_name', 'created_by', 'updated_by', 'deleted_at',
    ];
    protected $dates = ['deleted_at'];
    protected $hidden=[
        'created_by', 'updated_by', 'deleted_at', 'created_at', 'updated_at'
    ];

    public function company()
    {
        return $this->belongsTo(CompanyBasicInfo::class);
    }
}
