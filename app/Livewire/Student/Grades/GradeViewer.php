<?php

namespace App\Livewire\Student\Grades;

use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Student;
use App\Models\SchoolYear;
use App\Models\Enrollment;
use App\Models\Subject;
use App\Models\StudentGrade;
use Illuminate\Support\Facades\Auth;

#[Title('My Grades')]
class GradeViewer extends Component
{
    public ?Student $student = null;
    public $schoolYears = [];
    public $selectedSchoolYearId;
    public $enrollment;
    public $grades = [];

    public function mount(): void
    {
        $student = Student::where('user_id', Auth::id())->first();
        if (!$student) return;

        $this->student = $student;

        // Get all school years the student was enrolled in ordered latest first
        $this->schoolYears = SchoolYear::whereHas('enrollments', function ($q) use ($student) {
            $q->where('student_id', $student->id);
        })->orderByDesc('start_date')->get();

        // Default to active school year if enrolled, else latest
        $activeYear = $this->schoolYears->firstWhere('is_active', true);
        $this->selectedSchoolYearId = $activeYear
            ? $activeYear->id
            : $this->schoolYears->first()?->id;

        $this->loadGrades();
    }

    public function loadGrades(): void
    {
        if (!$this->selectedSchoolYearId) return;

        // Get enrollment for selected year
        $this->enrollment = Enrollment::with(['gradeLevel', 'section', 'schoolYear'])
            ->where('student_id', $this->student->id)
            ->where('school_year_id', $this->selectedSchoolYearId)
            ->first();

        // Get all grades for selected year grouped by subject
        $rawGrades = StudentGrade::with('subject')
            ->where('student_id', $this->student->id)
            ->where('school_year_id', $this->selectedSchoolYearId)
            ->get();

        // Group by subject_id
        $grouped = $rawGrades->groupBy('subject_id');

        $this->grades = $grouped->map(function ($quarters) {
            $subject = $quarters->first()->subject;
            $q1 = $quarters->firstWhere('quarter', 1)?->quarter_grade;
            $q2 = $quarters->firstWhere('quarter', 2)?->quarter_grade;
            $q3 = $quarters->firstWhere('quarter', 3)?->quarter_grade;
            $q4 = $quarters->firstWhere('quarter', 4)?->quarter_grade;

            // Float casting but keep 0.00 as valid grade
            $q1 = $q1 !== null ? (float)$q1 : null;
            $q2 = $q2 !== null ? (float)$q2 : null;
            $q3 = $q3 !== null ? (float)$q3 : null;
            $q4 = $q4 !== null ? (float)$q4 : null;

            $available = collect([$q1, $q2, $q3, $q4])->filter(fn($v) => $v !== null)->values();
            $finalGrade = $available->count() > 0
                ? round($available->avg(), 2)
                : null;

            return [
                'subject_name' => $subject->name,
                'subject_code' => $subject->code,
                'q1'           => $q1,
                'q2'           => $q2,
                'q3'           => $q3,
                'q4'           => $q4,
                'final_grade'  => $finalGrade,
                'remarks'      => $finalGrade === null
                    ? 'Incomplete'
                    : ($finalGrade >= 75 ? 'Passed' : 'Failed'),
            ];
        })->values()->toArray();
    }

    public function selectSchoolYear(int $schoolYearId): void
    {
        $this->selectedSchoolYearId = $schoolYearId;
        $this->loadGrades();
    }

    public function render()
    {
        return view('livewire.student.grades.grade-viewer');
    }
}
