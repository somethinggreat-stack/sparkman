<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('client_id')->nullable();
            $table->string('authnet_subscription_id')->nullable()->unique();
            $table->string('name');
            $table->string('email');
            $table->string('plan')->nullable();
            $table->decimal('amount', 10, 2)->default(0);   // monthly amount
            $table->string('interval')->default('monthly');
            $table->string('status')->default('active');     // active | at_risk | past_due | cancelled | completed
            $table->unsignedInteger('failed_payments')->default(0);
            $table->date('started_at')->nullable();
            $table->date('next_billing_at')->nullable();
            $table->timestamp('last_payment_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
