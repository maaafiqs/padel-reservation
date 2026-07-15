<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_code',
        'user_id',
        'court_id',
        'coach_id',
        'reservation_date',
        'start_time',
        'end_time',
        'total_price',
        'discount_code',
        'discount_amount',
        'final_price',
        'status',
        'payment_proof'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function inventories()
    {
        return $this->belongsToMany(Inventory::class)->withPivot('quantity', 'price');
    }
}
