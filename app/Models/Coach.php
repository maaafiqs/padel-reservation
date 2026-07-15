<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'bio',
        'price_per_hour',
        'capacity',
        'phone',
        'image',
        'is_available'
    ];
}
