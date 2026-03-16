<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->string('key');
            $table->string('type')->nullable(); 
            $table->json('settings');
            $table->timestamps();

            $table->unique('supplier_id');
        });

        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE supplier_settings ALTER COLUMN settings TYPE jsonb USING settings::jsonb');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_settings');
    }
};
