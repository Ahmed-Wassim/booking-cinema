<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('seats') && Schema::hasColumn('seats', 'section_id')) {
            Schema::table('seats', function (Blueprint $table) {
                $table->dropConstrainedForeignId('section_id');
            });
        }

        Schema::dropIfExists('hall_sections');
    }

    public function down(): void
    {
        Schema::create('hall_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hall_id')->constrained('halls')->cascadeOnDelete();
            $table->string('name');
            $table->string('layout_type')->nullable();
            $table->json('base_config')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        if (Schema::hasTable('seats') && ! Schema::hasColumn('seats', 'section_id')) {
            Schema::table('seats', function (Blueprint $table) {
                $table->foreignId('section_id')->nullable()->constrained('hall_sections')->nullOnDelete()->after('hall_id');
            });
        }
    }
};
