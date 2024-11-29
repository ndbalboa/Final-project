<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add two-factor fields to the users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('two_factor_code', 6)->nullable(); // Store the 6-digit code
            $table->timestamp('two_factor_expires_at')->nullable(); // Store the expiration time
        });

        // You already have the 'sessions' table, but let's ensure it has the 'device_id' column for tracking the device
        Schema::table('sessions', function (Blueprint $table) {
            $table->string('device_id')->nullable(); // Add device_id to associate the session with a device
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the fields if the migration is rolled back
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['two_factor_code', 'two_factor_expires_at']);
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->dropColumn('device_id');
        });
    }
};