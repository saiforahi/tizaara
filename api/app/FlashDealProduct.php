<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlashDealProduct extends Model
{

    public function flashDeal()
    {
        return $this->belongsTo(FlashDeal::class,'flash_deal_id','id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
