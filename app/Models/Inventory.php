<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_code',
        'name',
        'description',
        'price',
        'stock',
        'image',
        'is_consumable',
    ];

    public function reservations()
    {
        return $this->belongsToMany(Reservation::class)->withPivot('quantity', 'price');
    }
}
