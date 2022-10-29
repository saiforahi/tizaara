<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table="orders";
    protected $fillable=[
        'name', 'email', 'phone', 'address', 'product_id', 'product',
        'product_variant', 'offer', 'billing_info', 'country_id', 'division_id',
        'city_id', 'zip_code', 'quantity', 'unit_id', 'unit_price', 'offer_price',
        'sub_total', 'shipping_charge', 'vat', 'total_amount','pay_amount', 'supplier_id', 'buyer_id',
        'payment_type', 'payment_info', 'status', 'order_status',
        'approval_status', 'approved_by', 'updated_by', 'ip', 'browser', 'transaction_id', 'currency',
    ];
    protected $hidden=['approved_by', 'updated_by', 'ip', 'browser',];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function supplier()
    {
        return $this->belongsTo(User::class,'supplier_id','id');
    }
    public function buyer()
    {
        return $this->belongsTo(User::class,'buyer_id','id');
    }
    public function approver()
    {
        return $this->belongsTo(Admin::class,'approved_by','id');
    }

}
