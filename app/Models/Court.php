<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'price_per_hour',
        'image',
        'status'
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }
}
