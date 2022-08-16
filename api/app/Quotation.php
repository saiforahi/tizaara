<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product', 'details', 'quantity', 'unit_id','user_id','status','is_cancel','note','updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function quotation_users()
    {
        return $this->hasMany(QuotationUser::class);
    }

    public function quotationUsers()
    {
        return $this->belongsToMany(QuotationUser::class,'quotation_users','quotation_id','user_id')->withPivot(['status','note','is_sale','response_at'])->withTimestamps();
    }
}
