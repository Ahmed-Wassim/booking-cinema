<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('discount_id')
                  ->nullable()
                  ->after('showtime_id')
                  ->constrained('discounts')
                  ->nullOnDelete();

            $table->decimal('subtotal', 10, 2)->default(0)->after('discount_id');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('subtotal');

            // total_price already exists — it stores the final payable amount
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('discount_id');
            $table->dropColumn(['subtotal', 'discount_amount']);
        });
    }
};
