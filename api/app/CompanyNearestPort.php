<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyNearestPort extends Model
{
    use SoftDeletes;

    protected $fillable =[
        'name', 'company_id','address', 'created_by',
        'updated_by', 'deleted_at',
    ];
    protected $dates=['deleted_at'];
    protected $hidden =[
        'created_by',
        'updated_by', 'deleted_at', 'created_at', 'updated_at'
    ];

    public function company()
    {
        return $this->belongsTo(CompanyBasicInfo::class);
    }
}
