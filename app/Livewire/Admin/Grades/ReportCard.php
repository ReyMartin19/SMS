<?php

namespace App\Livewire\Admin\Grades;

use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\StudentGrade;

#[Title('Report Cards')]
class ReportCard extends Component
{
    public $studentId;
    public $schoolYearId;

    public $students = [];
    public $schoolYears = [];

    public function mount()
    {
        $this->schoolYears = SchoolYear::orderBy('name', 'desc')->get();
        $this->students = Student::orderBy('last_name')->get();

        $activeYear = SchoolYear::where('is_active', true)->first();
        if ($activeYear) {
            $this->schoolYearId = $activeYear->id;
        }
    }

    public function getReportDataProperty()
    {
        if (!$this->studentId || !$this->schoolYearId) {
            return null;
        }

        $student = Student::find($this->studentId);
        $schoolYear = SchoolYear::find($this->schoolYearId);

        $grades = StudentGrade::with(['subject'])
            ->where('student_id', $this->studentId)
            ->where('school_year_id', $this->schoolYearId)
            ->get();

        // Group grades by subject
        $subjectsData = [];
        $totalFinalGrades = 0;
        $subjectsWithFinalGradeCount = 0;

        foreach ($grades->groupBy('subject_id') as $subjectId => $subjectGrades) {
            $subject = $subjectGrades->first()->subject;
            $q1 = $subjectGrades->where('quarter', 1)->first()?->quarter_grade;
            $q2 = $subjectGrades->where('quarter', 2)->first()?->quarter_grade;
            $q3 = $subjectGrades->where('quarter', 3)->first()?->quarter_grade;
            $q4 = $subjectGrades->where('quarter', 4)->first()?->quarter_grade;

            $finalGrade = null;
            if ($q1 !== null && $q2 !== null && $q3 !== null && $q4 !== null) {
                $finalGrade = round(($q1 + $q2 + $q3 + $q4) / 4, 2);
                $totalFinalGrades += $finalGrade;
                $subjectsWithFinalGradeCount++;
            }

            $subjectsData[] = [
                'subject_name' => $subject->name,
                'q1' => $q1,
                'q2' => $q2,
                'q3' => $q3,
                'q4' => $q4,
                'final_grade' => $finalGrade,
                'remarks' => $finalGrade !== null ? ($finalGrade >= 75 ? 'Passed' : 'Failed') : 'Incomplete',
            ];
        }

        $generalAverage = $subjectsWithFinalGradeCount > 0 ? round($totalFinalGrades / $subjectsWithFinalGradeCount, 2) : null;

        return [
            'student' => $student,
            'schoolYear' => $schoolYear,
            'subjects' => $subjectsData,
            'general_average' => $generalAverage,
        ];
    }

    public function render()
    {
        return view('livewire.admin.grades.report-card', [
            'reportData' => $this->reportData
        ]);
    }
}
