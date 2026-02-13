<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Film extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'duration',
        'age_rating',
        'release_date'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }
}
