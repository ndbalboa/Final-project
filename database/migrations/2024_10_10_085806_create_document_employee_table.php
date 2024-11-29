<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('document_employee', function (Blueprint $table) {
            $table->id();
            // Foreign key for the document
            $table->foreignId('document_id')->constrained('documents')->onDelete('cascade');
            // Foreign key for the employee
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->timestamps(); // Optional, but useful for tracking association creation time
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_employee');
    }
}
