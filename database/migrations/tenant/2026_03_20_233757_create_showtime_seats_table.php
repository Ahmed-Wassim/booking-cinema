<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('showtime_seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('showtime_id')->constrained('showtimes')->onDelete('cascade');
            $table->foreignId('seat_id')->constrained('seats')->onDelete('cascade');
            $table->string('status')->default('available'); // available, reserved, booked, cancelled
            $table->timestamp('reserved_until')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            
            $table->unique(['showtime_id', 'seat_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('showtime_seats');
    }
};
