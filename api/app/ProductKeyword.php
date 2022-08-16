<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductKeyword extends Model
{
    protected $fillable = ['product_id', 'key_name', 'created_by', 'updated_by', 'deleted_by',];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
