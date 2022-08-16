<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class discount_variation extends Model
{
    protected $fillable=['product_id','percent_off','min_qty'];
    public function Product()
    {
        return $this->belongsTo(Product::class);
    }
}
