<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('showtimes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('film_id')->constrained()->cascadeOnDelete();
            $table->foreignId('studio_id')->constrained()->cascadeOnDelete();
            $table->date('show_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('price', 12, 2);
            $table->timestamps();

            $table->index('film_id');
            $table->index('show_date');
            $table->unique(['studio_id', 'show_date', 'start_time'], 'unique_showtime_slot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('showtimes');
    }
};
