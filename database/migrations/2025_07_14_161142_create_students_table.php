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
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('last_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('birth_date')->nullable();
            $table->string('student_number')->unique()->nullable();
            $table->string('filename')->nullable();
            $table->string('program')->nullable();
            $table->string('year_graduated')->nullable();
            $table->foreignId('doc_id')->nullable()->constrained('documents')->onDelete('cascade');;
            $table->timestamp('date_time_created')->nullable();
            $table->timestamp('date_time_updated')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
