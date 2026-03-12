<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('plans')->cascadeOnDelete();
            $table->string('transaction_ref')->unique()->nullable(); // PayTabs tran_ref
            $table->string('payment_token')->nullable();             // PayTabs cart_id
            $table->string('status')->default('pending');            // pending, paid, failed
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('AED');
            $table->json('callback_data')->nullable();               // raw PayTabs response
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
