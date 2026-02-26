<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Showtime extends Model
{
    use HasFactory;

    protected $fillable = [
        'film_id',
        'studio_id',
        'show_date',
        'start_time',
        'end_time',
        'price',
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
            try {
                $seats = $showtime->studio->seats;

                $showtimeSeats = $seats->map(fn ($seat) => [
                    'showtime_id' => $showtime->id,
                    'seat_id' => $seat->id,
                    'status' => 'available',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                \App\Models\ShowtimeSeat::insert($showtimeSeats->toArray());
            } catch (\Exception $e) {
                \Log::error('Failed to create showtime seats: '.$e->getMessage());
                throw $e;
            }
        });
    }
}
