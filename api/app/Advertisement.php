<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = [
        'add_no', 'banner', 'url'
    ];
}
