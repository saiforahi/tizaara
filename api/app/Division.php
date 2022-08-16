<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends Model
{
    use SoftDeletes;

    const STATUS_AVAILABLE = 1;
    const STATUS_NOT_AVAILABLE = 0;

    const STATUS = [
        self::STATUS_AVAILABLE,
        self::STATUS_NOT_AVAILABLE,
    ];

    protected $fillable = [
        'name', 'country_id', 'status', 'created_by', 'updated_by'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->hasMany(City::class);
    }
}
