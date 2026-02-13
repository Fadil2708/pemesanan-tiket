<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;  

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'showtime_id',
        'booking_code',
        'total_price',
        'status',
        'payment_method'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function showtime()
    {
        return $this->belongsTo(Showtime::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
