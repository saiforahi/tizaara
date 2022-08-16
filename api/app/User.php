<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Musonza\Chat\Traits\Messageable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    use Messageable;

    const STATUS_VERIFY_REQUEST = 1;
    const STATUS_APPROVED = 2;
    const STATUS_BLOCK = 3;

    protected $fillable = [
        'account_type', 'username', 'verificationToken', 'first_name', 'last_name', 'email', 'mobile', 'password', 'ip_address',
        'photo_type','photo','is_verified','status','membership_plan_id'
    ];

    protected $hidden = [
        'password',
    ];

    protected $appends = ['full_name'];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function companyBasicInfo()
    {
        return $this->hasOne(CompanyBasicInfo::class);
    }

    public function companyDetails()
    {
        return $this->hasOne(CompanyDetails::class);
    }

    public function guestUserSetting()
    {
        return $this->belongsTo(GuestUserSetting::class);
    }

    public function membershipPlan()
    {
        return $this->hasOne(MembershipPlan::class,'id','membership_plan_id');
    }

    public function getAllowedProducts()
    {
        if ($this->membershipPlan) {
            return $this->membershipPlan->no_of_allowed_products;
        }
        return $this->guestUserSetting->max_allowed_products;
    }

    public function getAllowedKeywords()
    {
        if ($this->membershipPlan) {
            return $this->membershipPlan->no_of_allowed_keywords;
        }
        return $this->guestUserSetting->max_allowed_keywords;
    }

    public function messageable1()
    {
        return $this->morphMany(ChatConversationType::class,'messageable1');
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }
    public function verifyRequest()
    {
        return $this->hasMany(VerifyRequest::class);
    }
}
