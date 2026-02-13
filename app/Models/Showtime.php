<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Showtime extends Model
{
    use HasFactory;

    protected $fillable = [
        'film_id',
        'studio_id',
        'show_date',
        'start_time',
        'end_time',
        'price'
    ];

    public function film()
    {
        return $this->belongsTo(Film::class);
    }

    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }

    public function showtimeSeats()
    {
        return $this->hasMany(\App\Models\ShowtimeSeat::class);
    }

    public function seats()
    {
        return $this->hasMany(ShowtimeSeat::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    protected static function booted()
    {
        static::created(function ($showtime) {

            $seats = $showtime->studio->seats;

            foreach ($seats as $seat) {
                \App\Models\ShowtimeSeat::create([
                    'showtime_id' => $showtime->id,
                    'seat_id'     => $seat->id,
                    'status'      => 'available'
                ]);
            }

        });
    }

}
