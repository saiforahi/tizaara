<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use SoftDeletes;

    const STATUS_AVAILABLE = 1;
    const STATUS_NOT_AVAILABLE = 0;

    const STATUS = [
        self::STATUS_AVAILABLE,
        self::STATUS_NOT_AVAILABLE,
    ];

    protected $fillable = [
        'name', 'division_id', 'status', 'created_by', 'updated_by'
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function area()
    {
        return $this->hasMany(Area::class);
    }
}
