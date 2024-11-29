<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_type_id')->constrained('document_types')->onDelete('cascade');  // Foreign key referencing 'document_types' table
            $table->string('document_no')->nullable();
            $table->string('series_no')->nullable();
            $table->date('date_issued')->nullable();
            $table->string('from_date')->nullable();
            $table->string('to_date')->nullable();
            $table->string('venue')->nullable();
            $table->string('destination')->nullable();
            $table->string('subject')->nullable();
            $table->text('description')->nullable();
            $table->json('employee_names')->nullable();
            $table->json('student_names')->nullable();
            $table->string('file_path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
}
