<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            // No longer downloading images locally — TMDB CDN is used directly.
            $table->dropColumn(['local_poster_path', 'local_backdrop_path']);

            // Popularity score from TMDB, useful for tenant-side sorting/recommendations.
            $table->decimal('popularity', 10, 3)->nullable()->after('language');
        });
    }

    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropColumn('popularity');
            $table->string('local_poster_path')->nullable();
            $table->string('local_backdrop_path')->nullable();
        });
    }
};
