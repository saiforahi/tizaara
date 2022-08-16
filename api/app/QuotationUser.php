<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationUser extends Model
{
    use SoftDeletes;
    protected $fillable=['quotation_id', 'user_id', 'status', 'note', 'is_sale', 'response_at',];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
