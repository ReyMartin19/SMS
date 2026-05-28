<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Enrollment;
use App\Services\SubjectAssignmentService;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $enrollments = Enrollment::with(['student', 'gradeLevel'])->get();
        $service = new SubjectAssignmentService();

        foreach ($enrollments as $enrollment) {
            if ($enrollment->student && $enrollment->gradeLevel) {
                // If it is Grade 12, it should use the track from enrollment, if set.
                $track = $enrollment->track;
                
                $service->assignSubjects(
                    $enrollment->student,
                    $enrollment,
                    $enrollment->gradeLevel,
                    $track
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Deleting the records is not strictly necessary for rollback as the table is dropped, 
        // but we can clear them.
        \App\Models\StudentSubjectEnrollment::truncate();
    }
};
