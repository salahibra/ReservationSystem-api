<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // fillable fields
    protected $fillable = [
        'name',
        'detail'
    ];
}
