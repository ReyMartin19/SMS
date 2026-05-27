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
    public ?SchoolYear $activeSchoolYear = null;
    public ?Enrollment $enrollment = null;
    public string $activeTab = 'Q1';

    public function mount()
    {
        $this->student = Student::where('user_id', Auth::id())->first();
        $this->activeSchoolYear = SchoolYear::where('is_active', true)->first();
        
        if ($this->student && $this->activeSchoolYear) {
            $this->enrollment = Enrollment::with(['section', 'gradeLevel'])
                ->where('student_id', $this->student->id)
                ->where('school_year_id', $this->activeSchoolYear->id)
                ->first();
        }
    }

    public function setActiveTab(string $tab)
    {
        if (in_array($tab, ['Q1', 'Q2', 'Q3', 'Q4', 'Final'])) {
            $this->activeTab = $tab;
        }
    }

    public function render()
    {
        $subjects = collect();
        $grades = collect(); 
        $finalGrades = []; 
        $overallAverage = null;

        if ($this->student && $this->activeSchoolYear && $this->enrollment) {
            // Get subjects assigned to student's section
            $subjects = Subject::whereIn('id', function ($query) {
                $query->select('subject_id')
                    ->from('teacher_assignments')
                    ->where('section_id', $this->enrollment->section_id)
                    ->where('school_year_id', $this->activeSchoolYear->id);
            })->get();

            // Load all grade records for this school year
            $grades = StudentGrade::where('student_id', $this->student->id)
                ->where('school_year_id', $this->activeSchoolYear->id)
                ->get()
                ->groupBy('subject_id'); 

            // Compute final grade per subject and overall average
            $subjectFinals = [];
            foreach ($subjects as $subject) {
                $subjectGrades = $grades->get($subject->id) ?? collect();
                
                // Get grades for each quarter
                $q1 = $subjectGrades->firstWhere('quarter', 1)?->quarter_grade;
                $q2 = $subjectGrades->firstWhere('quarter', 2)?->quarter_grade;
                $q3 = $subjectGrades->firstWhere('quarter', 3)?->quarter_grade;
                $q4 = $subjectGrades->firstWhere('quarter', 4)?->quarter_grade;

                $finalGradeVal = collect([$q1, $q2, $q3, $q4])->filter(fn($v) => !is_null($v));
                if ($finalGradeVal->isNotEmpty()) {
                    $finalGrades[$subject->id] = $finalGradeVal->avg();
                    $subjectFinals[] = $finalGrades[$subject->id];
                } else {
                    $finalGrades[$subject->id] = null;
                }
            }

            if (count($subjectFinals) > 0) {
                $overallAverage = collect($subjectFinals)->avg();
            }
        }

        return view('livewire.student.grades.grade-viewer', [
            'subjects' => $subjects,
            'grades' => $grades,
            'finalGrades' => $finalGrades,
            'overallAverage' => $overallAverage,
        ]);
    }
}
