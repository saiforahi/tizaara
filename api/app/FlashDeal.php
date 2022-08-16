<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlashDeal extends Model
{

    public function color()
    {
        return $this->belongsTo(Color::class,'bg_color','id');
    }

    public function flashDealProducts()
    {
        return $this->hasMany(FlashDealProduct::class);
    }
}
