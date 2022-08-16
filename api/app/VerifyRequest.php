<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VerifyRequest extends Model
{
    protected $fillable = [
        'user_id', 'company_basic_info_id', 'message', 'note', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function companyBasicInfo()
    {
        return $this->belongsTo(CompanyBasicInfo::class);
    }
}
