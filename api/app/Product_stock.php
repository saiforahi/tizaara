<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product_stock extends Model
{
    protected $fillable=['product_id', 'variant', 'sku', 'price', 'qty',];
    public function Product()
    {
        return $this->belongsTo(Product::class);
    }
}
