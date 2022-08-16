<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyFactory extends Model
{
    use SoftDeletes;

    protected $fillable =[
        'name', 'company_id', 'factory_space', 'staff_number_id', 'rnd_staff_id',
        'production_line_id','annual_output_id', 'created_by',
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
    public function staffNumber()
    {
        return $this->belongsTo(StaffNumber::class);
    }
    public function rndStaff()
    {
        return $this->belongsTo(RndStaff::class);
    }
    public function productionLine()
    {
        return $this->belongsTo(ProductionLine::class);
    }
    public function annualOutput()
    {
        return $this->belongsTo(AnnualOutput::class);
    }
}
