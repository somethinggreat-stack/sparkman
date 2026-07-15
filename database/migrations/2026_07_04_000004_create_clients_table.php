<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('lead_id')->nullable()->constrained()->nullOnDelete();

            // Identity
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('suffix')->nullable();

            // Contact
            $table->string('email');
            $table->string('phone');

            // Address
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('state', 40)->nullable();
            $table->string('zip', 20)->nullable();

            // Verification (SSN encrypted at rest via model cast)
            $table->text('ssn')->nullable();
            $table->date('dob')->nullable();

            $table->string('service')->default('credit-repair'); // which paid service
            $table->string('status')->default('active');          // active | paused | cancelled
            $table->timestamp('crc_synced_at')->nullable();
            $table->string('crc_contact_id')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
