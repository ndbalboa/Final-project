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
        Schema::create('mails', function (Blueprint $table) {
            $table->id();
            $table->string('to');
            $table->string('from');
            $table->enum('priority', ['Very High', 'High', 'Normal', 'Low', 'Very Low']);
            $table->enum('status', ['undelivered', 'delivered']);
            $table->text('description')->nullable();
            $table->timestamp('date_received')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mails');
    }
};
