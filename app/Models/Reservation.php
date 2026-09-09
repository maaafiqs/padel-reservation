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

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('reservation_date', today());
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }
}
