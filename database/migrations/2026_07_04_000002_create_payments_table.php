<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('client_id')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('plan')->nullable();          // individual | aggressive | couple | funding | custom
            $table->string('description')->nullable();
            $table->decimal('amount', 10, 2)->default(0); // charged (down payment)
            $table->decimal('monthly_amount', 10, 2)->nullable();
            $table->string('type')->default('one_time');  // one_time | subscription_initial
            $table->string('authnet_transaction_id')->nullable();
            $table->string('authnet_subscription_id')->nullable();
            $table->string('card_type')->nullable();
            $table->string('card_last4', 8)->nullable();
            $table->string('status')->default('pending'); // pending | paid | failed | refunded
            $table->string('onboarding_token')->nullable()->unique();
            $table->timestamp('onboarded_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
