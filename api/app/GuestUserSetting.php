<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuestUserSetting extends Model
{
    protected $fillable = [
        'max_allowed_products', 'max_allowed_keywords', 'created_by', 'updated_by', 'deleted_by'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
