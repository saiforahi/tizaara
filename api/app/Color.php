<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = [
        'name', 'code'
    ];
    protected $hidden=['created_at','updated_at'];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }
}
