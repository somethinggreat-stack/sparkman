<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('source_url')->nullable()->after('message');
            $table->string('ip_address', 64)->nullable()->after('source_url');
            $table->json('meta')->nullable()->after('ip_address');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['source_url', 'ip_address', 'meta']);
        });
    }
};
