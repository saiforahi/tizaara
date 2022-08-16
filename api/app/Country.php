<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;

    const STATUS_AVAILABLE = 1;
    const STATUS_NOT_AVAILABLE = 0;

    const STATUS = [
        self::STATUS_AVAILABLE,
        self::STATUS_NOT_AVAILABLE,
    ];

    protected $fillable = [
        'name', 'code', 'code_a3', 'code_n3', 'lat', 'long', 'status', 'created_by', 'updated_by'
    ];

    public function division()
    {
        return $this->hasMany(Division::class);
    }
}
