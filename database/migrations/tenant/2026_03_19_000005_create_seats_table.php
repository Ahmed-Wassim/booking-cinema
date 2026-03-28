<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hall_id')->constrained('halls')->cascadeOnDelete();
            $table->foreignId('section_id')->nullable()->constrained('hall_sections')->nullOnDelete();
            $table->foreignId('price_tier_id')->nullable()->constrained('price_tiers')->nullOnDelete();
            $table->string('row', 10);
            $table->string('number', 10);
            $table->decimal('pos_x', 8, 4);
            $table->decimal('pos_y', 8, 4);
            $table->decimal('rotation', 6, 2)->default(0);
            $table->decimal('width', 5, 2)->default(15.0);
            $table->decimal('height', 5, 2)->default(15.0);
            $table->enum('shape', ['rect', 'circle', 'rounded_rect', 'sofa', 'wheelchair'])->default('rect');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
