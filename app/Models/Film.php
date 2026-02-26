<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'poster',
        'description',
        'duration',
        'age_rating',
        'release_date',
    ];

    protected $casts = [
        'release_date' => 'date',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'film_category');
    }

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }

    /**
     * Get available showtimes for today and future
     */
    public function upcomingShowtimes()
    {
        return $this->showtimes()
            ->where('show_date', '>=', now()->toDateString())
            ->orderBy('show_date')
            ->orderBy('start_time')
            ->get();
    }

    /**
     * Check if film is currently showing
     */
    public function isNowShowing(): bool
    {
        return $this->showtimes()
            ->where('show_date', '>=', now()->toDateString())
            ->exists();
    }
}
