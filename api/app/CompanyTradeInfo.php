<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyTradeInfo extends Model
{
    use SoftDeletes;

    protected $fillable=[
        'name', 'company_id', 'export_started_year', 'annual_revenue_id',
        'export_percent_id', 'created_by', 'updated_by', 'deleted_at',
    ];
    protected $dates = ['deleted_at'];
    protected $hidden=[
        'created_by', 'updated_by', 'deleted_at', 'created_at', 'updated_at'
    ];

    public function company()
    {
        return $this->belongsTo(CompanyBasicInfo::class);
    }

    public function annualRevenue()
    {
        return $this->belongsTo(AnnualRevenue::class);
    }
    public function exportPercent()
    {
        return $this->belongsTo(ExportPercent::class);
    }
}
