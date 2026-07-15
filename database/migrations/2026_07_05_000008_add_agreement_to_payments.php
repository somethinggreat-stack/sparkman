<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->timestamp('agreement_signed_at')->nullable()->after('onboarded_at');
            $table->longText('agreement_signature')->nullable()->after('agreement_signed_at'); // client-drawn signature (PNG data URL)
            $table->string('agreement_ip', 64)->nullable()->after('agreement_signature');
            $table->string('agreement_name', 160)->nullable()->after('agreement_ip'); // name as signed
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['agreement_signed_at', 'agreement_signature', 'agreement_ip', 'agreement_name']);
        });
    }
};
