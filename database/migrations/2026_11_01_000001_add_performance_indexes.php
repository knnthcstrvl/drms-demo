<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->index('filename', 'students_filename_index');
            $table->index('program', 'students_program_index');
            $table->index('year_graduated', 'students_year_graduated_index');
            $table->index('date_time_created', 'students_created_at_index');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('date_time_created', 'users_created_at_index');
            $table->index('role', 'users_role_index');
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->index('date_time', 'audit_logs_date_time_index');
        });
    }

    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex('audit_logs_date_time_index');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_created_at_index');
            $table->dropIndex('users_role_index');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('students_filename_index');
            $table->dropIndex('students_program_index');
            $table->dropIndex('students_year_graduated_index');
            $table->dropIndex('students_created_at_index');
        });
    }
};
