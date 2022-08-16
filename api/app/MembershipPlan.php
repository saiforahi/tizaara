<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MembershipPlan extends Model
{
    protected $fillable = [
        'name', 'benefit', 'amount', 'duration', 'buffer_time', 'no_of_allowed_products', 'no_of_allowed_keywords', 'no_of_allowed_rfq',
        'no_of_top_adds', 'created_by'
    ];
    protected $hidden = ['created_at', 'updated_at'];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
