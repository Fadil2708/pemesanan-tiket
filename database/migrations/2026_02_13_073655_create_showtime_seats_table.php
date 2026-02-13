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
        Schema::create('showtime_seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('showtime_id')->constrained()->cascadeOnDelete();
            $table->foreignId('seat_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['available','locked','booked'])->default('available');
            $table->timestamp('locked_at')->nullable();
            $table->timestamps();

            $table->unique(['showtime_id','seat_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('showtime_seats');
    }
};
