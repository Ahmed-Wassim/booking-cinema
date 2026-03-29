<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'currency')) {
                $table->string('currency', 10)->default(config('paytabs.currency', 'AED'))->after('total_price');
            }
        });

        Schema::table('booking_seats', function (Blueprint $table) {
            if (! Schema::hasColumn('booking_seats', 'currency')) {
                $table->string('currency', 10)->default(config('paytabs.currency', 'AED'))->after('price');
            }
        });

        Schema::table('showtime_seats', function (Blueprint $table) {
            if (! Schema::hasColumn('showtime_seats', 'currency')) {
                $table->string('currency', 10)->default(config('paytabs.currency', 'AED'))->after('price');
            }
        });

        Schema::table('price_tiers', function (Blueprint $table) {
            if (! Schema::hasColumn('price_tiers', 'currency')) {
                $table->string('currency', 10)->default(config('paytabs.currency', 'AED'))->after('price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('currency');
        });

        Schema::table('booking_seats', function (Blueprint $table) {
            $table->dropColumn('currency');
        });

        Schema::table('showtime_seats', function (Blueprint $table) {
            $table->dropColumn('currency');
        });

        Schema::table('price_tiers', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
    }
};