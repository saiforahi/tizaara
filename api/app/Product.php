<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function product_stock()
    {
        return $this->hasMany(Product_stock::class);
    }

    public function discount_variation_data()
    {
        return $this->hasMany(discount_variation::class);
    }

    public function price_variation()
    {
        return $this->hasMany(price_variation::class);
    }

    public function productKeywords()
    {
        return $this->hasMany(ProductKeyword::class);
    }

    public function subsubcategories()
    {
        return $this->belongsTo(SubSubCategory::class, 'subsubcategory_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id');
    }
    public function subsubcategory()
    {
        return $this->belongsTo(SubSubCategory::class, 'subsubcategory_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    public function flashDealProducts()
    {
        return $this->hasMany(FlashDealProduct::class);
    }
    public function productFavorites()
    {
        return $this->hasMany(ProductFavorite::class);
    }

    public function productReviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function productRating()
    {
        return $this->hasMany(ProductRating::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function avgRating()
    {
        return $this->productRating()->avg('rating');
    }
    public function productColors()
    {
        return $this->belongsToMany(Color::class)->withTimestamps();
    }

}
