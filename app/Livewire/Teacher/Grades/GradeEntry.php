<?php

namespace App\Livewire\Teacher\Grades;

use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\ActivityLog;
use App\Models\SchoolYear;
use App\Models\TeacherAssignment;
use App\Models\Enrollment;
use App\Models\StudentGrade;
use Illuminate\Support\Facades\Auth;

#[Title('Grade Entry')]
class GradeEntry extends Component
{
    public $schoolYearId;
    public $assignmentId;
    public $quarter;

    public $schoolYears = [];
    public $assignments = [];
    
    // For bulk entry
    public $grades = [];

    public function mount()
    {
        $this->schoolYears = SchoolYear::orderBy('name', 'desc')->get();
        $activeYear = SchoolYear::where('is_active', true)->first();
        if ($activeYear) {
            $this->schoolYearId = $activeYear->id;
            $this->loadAssignments();
        }
    }

    public function updatedSchoolYearId()
    {
        $this->assignmentId = null;
        $this->quarter = null;
        $this->grades = [];
        $this->loadAssignments();
    }

    public function updatedAssignmentId()
    {
        $this->grades = [];
        if ($this->assignmentId && $this->quarter) {
            $this->loadGrades();
        }
    }

    public function updatedQuarter()
    {
        $this->grades = [];
        if ($this->assignmentId && $this->quarter) {
            $this->loadGrades();
        }
    }

    public function loadAssignments()
    {
        if ($this->schoolYearId) {
            $teacher = \App\Models\Teacher::where('user_id', Auth::id())->first();
            if ($teacher) {
                $this->assignments = TeacherAssignment::with(['subject', 'section'])
                    ->where('teacher_id', $teacher->id)
                    ->where('school_year_id', $this->schoolYearId)
                    ->get();
            }
        }
    }

    public function loadGrades()
    {
        $assignment = TeacherAssignment::find($this->assignmentId);
        if (!$assignment) return;

        // Get enrolled students
        $enrollments = Enrollment::with('student')
            ->where('school_year_id', $this->schoolYearId)
            ->where('section_id', $assignment->section_id)
            ->where('status', 'enrolled')
            ->get();

        foreach ($enrollments as $enrollment) {
            $grade = StudentGrade::firstOrCreate([
                'student_id' => $enrollment->student_id,
                'subject_id' => $assignment->subject_id,
                'section_id' => $assignment->section_id,
                'school_year_id' => $this->schoolYearId,
                'quarter' => $this->quarter,
            ]);

            $this->grades[$grade->id] = [
                'student_name' => $enrollment->student->last_name . ', ' . $enrollment->student->first_name,
                'written_works_score' => $grade->written_works_score,
                'performance_task_score' => $grade->performance_task_score,
                'quarterly_assessment_score' => $grade->quarterly_assessment_score,
                'quarter_grade' => $grade->quarter_grade,
                'remarks' => $grade->remarks,
            ];
        }
    }

    public function rules()
    {
        return [
            'grades.*.student_name' => 'nullable',
            'grades.*.written_works_score' => 'nullable|numeric|min:0|max:100',
            'grades.*.performance_task_score' => 'nullable|numeric|min:0|max:100',
            'grades.*.quarterly_assessment_score' => 'nullable|numeric|min:0|max:100',
            'grades.*.quarter_grade' => 'nullable',
            'grades.*.remarks' => 'nullable',
        ];
    }

    public function saveGrades()
    {
        $this->validate();

        foreach ($this->grades as $gradeId => $data) {
            $grade = StudentGrade::find($gradeId);
            if ($grade) {
                $grade->written_works_score = $data['written_works_score'] !== '' ? $data['written_works_score'] : null;
                $grade->performance_task_score = $data['performance_task_score'] !== '' ? $data['performance_task_score'] : null;
                $grade->quarterly_assessment_score = $data['quarterly_assessment_score'] !== '' ? $data['quarterly_assessment_score'] : null;
                $grade->computeQuarterGrade();
                $grade->save();
                
                // Update component state
                $this->grades[$gradeId]['quarter_grade'] = $grade->quarter_grade;
                $this->grades[$gradeId]['remarks'] = $grade->remarks;
            }
        }

        $assignment = TeacherAssignment::with(['subject', 'section'])->find($this->assignmentId);
        if ($assignment) {
            ActivityLog::log(
                'entered_grades', 'grades',
                "Entered grades for section {$assignment->section->name} — Subject {$assignment->subject->name} Q{$this->quarter}",
                [
                    'new_values' => [
                        'section_id' => $assignment->section_id,
                        'subject_id' => $assignment->subject_id,
                        'quarter'    => $this->quarter,
                    ],
                ]
            );
        }

        session()->flash('success', 'Grades saved successfully!');
    }

    public function render()
    {
        return view('livewire.teacher.grades.grade-entry');
    }
}
