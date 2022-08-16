<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable=['user_id','product_id','comment','is_approved','approved_by'];
    protected $hidden=['updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
