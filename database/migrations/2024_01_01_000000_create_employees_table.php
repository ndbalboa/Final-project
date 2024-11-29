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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('lastName');
            $table->string('firstName');
            $table->string('middleName')->nullable();
            $table->enum('sex', ['Male', 'Female', 'Other']);
            $table->string('civilStatus')->nullable();
            $table->date('dateOfBirth');
            $table->string('religion')->nullable();
            $table->string('emailAddress')->unique();
            $table->string('phoneNumber')->unique();
            $table->string('gsisId')->unique()->nullable();
            $table->string('pagibigId')->unique()->nullable();
            $table->string('philhealthId')->unique()->nullable();
            $table->string('sssNo')->unique()->nullable();
            $table->string('agencyEmploymentNo')->unique()->nullable();
            $table->string('taxId')->unique()->nullable();
            $table->string('academicRank');
            $table->string('universityPosition');
            $table->string('profileImage')->nullable();
            $table->string('permanent_street')->nullable();
            $table->string('permanent_barangay')->nullable();
            $table->string('permanent_city')->nullable();
            $table->string('permanent_province')->nullable();
            $table->string('permanent_country')->nullable();
            $table->string('permanent_zipcode')->nullable();
            $table->string('residential_street')->nullable();
            $table->string('residential_barangay')->nullable();
            $table->string('residential_city')->nullable();
            $table->string('residential_province')->nullable();
            $table->string('residential_country')->nullable();
            $table->string('residential_zipcode')->nullable();
            $table->string('department')->nullable();
            $table->softDeletes(); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
