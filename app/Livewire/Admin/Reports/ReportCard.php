<?php

namespace App\Livewire\Admin\Reports;

use App\Models\SchoolYear;
use App\Models\Enrollment;
use App\Models\GradeLevel;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Student;
use App\Models\StudentGrade;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Report Cards')]
class ReportCard extends Component
{
    public $studentId;
    public $schoolYearId;

    public function mount()
    {
        $this->studentId = request()->query('student_id');
        
        $selectedSy = request()->query('school_year_id');
        if ($selectedSy) {
            $this->schoolYearId = $selectedSy;
        } else {
            $activeSy = SchoolYear::where('is_active', true)->first() ?? SchoolYear::latest()->first();
            $this->schoolYearId = $activeSy?->id;
        }
    }

    public function getStudentProperty()
    {
        return Student::find($this->studentId);
    }

    public function getSchoolYearsProperty()
    {
        return SchoolYear::orderBy('name', 'desc')->get();
    }

    public function getEnrollmentProperty()
    {
        if (!$this->studentId || !$this->schoolYearId) {
            return null;
        }

        return Enrollment::where('student_id', $this->studentId)
            ->where('school_year_id', $this->schoolYearId)
            ->first();
    }

    public function getGradesDataProperty()
    {
        $enrollment = $this->enrollment;
        if (!$enrollment) {
            return [];
        }

        // Fetch subjects for this grade level
        $subjects = Subject::where('grade_level_id', $enrollment->grade_level_id)->get();

        // Fetch all student grades for this school year and student
        $grades = StudentGrade::where('student_id', $this->studentId)
            ->where('school_year_id', $this->schoolYearId)
            ->get();

        $gradesData = [];

        foreach ($subjects as $subject) {
            $q1 = $grades->where('subject_id', $subject->id)->where('quarter', 1)->first()?->quarter_grade;
            $q2 = $grades->where('subject_id', $subject->id)->where('quarter', 2)->first()?->quarter_grade;
            $q3 = $grades->where('subject_id', $subject->id)->where('quarter', 3)->first()?->quarter_grade;
            $q4 = $grades->where('subject_id', $subject->id)->where('quarter', 4)->first()?->quarter_grade;

            // Cast scores to float if not null
            $q1 = $q1 !== null ? (float)$q1 : null;
            $q2 = $q2 !== null ? (float)$q2 : null;
            $q3 = $q3 !== null ? (float)$q3 : null;
            $q4 = $q4 !== null ? (float)$q4 : null;

            // Calculate final grade: simple average of non-null quarters
            $activeGrades = array_filter([$q1, $q2, $q3, $q4], fn($v) => $v !== null);
            $finalGrade = count($activeGrades) > 0 ? (array_sum($activeGrades) / count($activeGrades)) : null;

            $remarks = null;
            if ($finalGrade !== null) {
                $remarks = $finalGrade >= 75 ? 'Passed' : 'Failed';
            }

            $gradesData[] = [
                'subject_id' => $subject->id,
                'subject_name' => $subject->name,
                'subject_code' => $subject->code,
                'q1' => $q1,
                'q2' => $q2,
                'q3' => $q3,
                'q4' => $q4,
                'final_grade' => $finalGrade,
                'remarks' => $remarks
            ];
        }

        return $gradesData;
    }

    public function getOverallAverageProperty()
    {
        $gradesData = $this->gradesData;
        if (empty($gradesData)) {
            return null;
        }

        $finalGrades = array_filter(array_column($gradesData, 'final_grade'), fn($v) => $v !== null);
        if (empty($finalGrades)) {
            return null;
        }

        return array_sum($finalGrades) / count($finalGrades);
    }

    public function getPromotionStatusProperty()
    {
        $gradesData = $this->gradesData;
        $overallAverage = $this->overallAverage;

        if (empty($gradesData) || $overallAverage === null) {
            return 'Incomplete';
        }

        // Must have at least one graded subject, and no failed subjects (grade < 75)
        $hasFailedSubject = false;
        $hasGrades = false;

        foreach ($gradesData as $data) {
            if ($data['final_grade'] !== null) {
                $hasGrades = true;
                if ($data['final_grade'] < 75) {
                    $hasFailedSubject = true;
                }
            }
        }

        if (!$hasGrades) {
            return 'Incomplete';
        }

        if ($overallAverage >= 75 && !$hasFailedSubject) {
            return 'Promoted';
        }

        return 'Retained';
    }

    public function render()
    {
        return view('livewire.admin.reports.report-card', [
            'student' => $this->student,
            'schoolYears' => $this->schoolYears,
            'enrollment' => $this->enrollment,
            'gradesData' => $this->gradesData,
            'overallAverage' => $this->overallAverage,
            'promotionStatus' => $this->promotionStatus,
        ]);
    }
}
