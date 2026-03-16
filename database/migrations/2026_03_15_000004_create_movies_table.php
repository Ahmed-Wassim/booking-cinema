<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->unsignedBigInteger('external_id');
            $table->string('title');
            $table->text('overview')->nullable();
            $table->string('poster_path')->nullable();
            $table->string('backdrop_path')->nullable();
            $table->string('local_poster_path')->nullable();
            $table->string('local_backdrop_path')->nullable();
            $table->date('release_date')->nullable();
            $table->unsignedInteger('runtime')->nullable();
            $table->string('language')->nullable();
            $table->timestamps();

            $table->unique(['supplier_id', 'external_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
