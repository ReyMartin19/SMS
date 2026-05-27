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
        // enrollments
        Schema::table('enrollments', function (Blueprint $table) {
            $table->index(['student_id', 'school_year_id']);
            $table->index('status');
        });

        // student_grades
        Schema::table('student_grades', function (Blueprint $table) {
            $table->index(['student_id', 'school_year_id']);
            $table->index(['section_id', 'subject_id', 'quarter']);
        });

        // activity_logs
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index(['user_id', 'module']);
            $table->index('created_at');
        });

        // announcements
        Schema::table('announcements', function (Blueprint $table) {
            $table->index(['audience', 'published_at']);
        });

        // students
        Schema::table('students', function (Blueprint $table) {
            $table->index('status');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['user_id']);
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->dropIndex(['audience', 'published_at']);
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'module']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('student_grades', function (Blueprint $table) {
            $table->dropIndex(['student_id', 'school_year_id']);
            $table->dropIndex(['section_id', 'subject_id', 'quarter']);
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex(['student_id', 'school_year_id']);
            $table->dropIndex(['status']);
        });
    }
};
