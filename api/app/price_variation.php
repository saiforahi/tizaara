<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class price_variation extends Model
{
    protected $fillable=['product_id', 'off_price', 'min_qty', 'max_qty',];

    public function Product()
    {
        return $this->belongsTo(Product::class);
    }
}
