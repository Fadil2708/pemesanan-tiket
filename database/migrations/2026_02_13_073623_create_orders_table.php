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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('showtime_id')->constrained()->cascadeOnDelete();
            $table->string('booking_code')->unique();
            $table->decimal('total_price', 12, 2);
            $table->enum('status', ['pending','paid','canceled'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('showtime_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
