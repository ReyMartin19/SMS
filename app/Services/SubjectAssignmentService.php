<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Enrollment;
use App\Models\GradeLevel;
use App\Models\Subject;
use App\Models\StudentSubjectEnrollment;

class SubjectAssignmentService
{
    public function assignSubjects(
        Student $student, 
        Enrollment $enrollment,
        GradeLevel $gradeLevel,
        ?string $track = null
    ): void {
        $subjects = collect();

        // Determine which subjects to assign
        if ($gradeLevel->type === 'elementary') {
            $subjects = Subject::where('type', 'elementary')
                               ->whereNull('track')
                               ->get();
        } elseif ($gradeLevel->type === 'junior_high') {
            $subjects = Subject::where('type', 'junior_high')
                               ->whereNull('track')
                               ->get();
        } elseif ($gradeLevel->type === 'senior_high') {
            if ($gradeLevel->order === 11) {
                // Grade 11 — fixed core subjects only
                $subjects = Subject::where('type', 'senior_high')
                                   ->whereNull('track')
                                   ->get();
            } else {
                // Grade 12 — track-based subjects
                $subjects = Subject::where('type', 'senior_high')
                                   ->where('track', $track)
                                   ->get();
            }
        }

        // Create student_subject_enrollment records
        foreach ($subjects as $subject) {
            StudentSubjectEnrollment::firstOrCreate([
                'student_id'    => $student->id,
                'subject_id'    => $subject->id,
                'school_year_id' => $enrollment->school_year_id,
            ], [
                'enrollment_id' => $enrollment->id,
            ]);
        }
    }
}
