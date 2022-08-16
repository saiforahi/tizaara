<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackagePayment extends Model
{
    const STATUS_PENDING = 0;
    const STATUS_PAID = 1;
    const STATUS_DUE = 2;

    const STATUSES = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_PAID => 'Paid',
        self::STATUS_DUE => 'Due',
    ];

    protected $fillable = [
        'user_id', 'membership_plan_id', 'payment_date', 'payment_type', 'amount', 'status',
    ];

    public function supplier()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function package()
    {
        return $this->belongsTo(MembershipPlan::class, 'membership_plan_id');
    }
}
