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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->string('seat_label')->nullable();
            $table->foreignId('seat_id')->nullable()->constrained('seats');
            $table->string('ticket_number')->unique();
            $table->string('qr_code')->unique();
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
            
            $table->index(['qr_code', 'used_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
