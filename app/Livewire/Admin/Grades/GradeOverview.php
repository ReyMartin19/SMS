<?php

namespace App\Livewire\Admin\Grades;

use Livewire\Component;
use App\Models\SchoolYear;
use App\Models\GradeLevel;
use App\Models\Section;
use App\Models\Subject;
use App\Models\StudentGrade;

class GradeOverview extends Component
{
    public $schoolYearId;
    public $gradeLevelId;
    public $sectionId;
    public $subjectId;
    public $quarter;

    public $sections = [];
    public $subjects = [];

    public $showForm = false;
    public $editingGradeId = null;
    public $written_works_score;
    public $performance_task_score;
    public $quarterly_assessment_score;

    public function mount()
    {
        $activeYear = SchoolYear::where('is_active', true)->first();
        if ($activeYear) {
            $this->schoolYearId = $activeYear->id;
        }
    }

    public function updatedGradeLevelId($value)
    {
        $this->sections = Section::where('grade_level_id', $value)->get();
        $this->subjects = Subject::where('grade_level_id', $value)->orWhereNull('grade_level_id')->get();
        $this->sectionId = null;
        $this->subjectId = null;
    }

    public function editGrade($gradeId)
    {
        $grade = StudentGrade::findOrFail($gradeId);
        $this->editingGradeId = $grade->id;
        $this->written_works_score = $grade->written_works_score;
        $this->performance_task_score = $grade->performance_task_score;
        $this->quarterly_assessment_score = $grade->quarterly_assessment_score;
        $this->showForm = true;
    }

    public function saveGrade()
    {
        $this->validate([
            'written_works_score' => 'nullable|numeric|min:0|max:100',
            'performance_task_score' => 'nullable|numeric|min:0|max:100',
            'quarterly_assessment_score' => 'nullable|numeric|min:0|max:100',
        ]);

        $grade = StudentGrade::findOrFail($this->editingGradeId);
        $grade->written_works_score = $this->written_works_score !== '' ? $this->written_works_score : null;
        $grade->performance_task_score = $this->performance_task_score !== '' ? $this->performance_task_score : null;
        $grade->quarterly_assessment_score = $this->quarterly_assessment_score !== '' ? $this->quarterly_assessment_score : null;
        $grade->computeQuarterGrade();
        $grade->save();

        $this->showForm = false;
        $this->editingGradeId = null;
        session()->flash('success', 'Grade overridden successfully.');
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->editingGradeId = null;
    }

    public function getGradesProperty()
    {
        if (!$this->schoolYearId || !$this->sectionId || !$this->subjectId || !$this->quarter) {
            return collect();
        }

        return StudentGrade::with(['student'])
            ->where('school_year_id', $this->schoolYearId)
            ->where('section_id', $this->sectionId)
            ->where('subject_id', $this->subjectId)
            ->where('quarter', $this->quarter)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.grades.grade-overview', [
            'grades' => $this->grades,
            'schoolYears' => SchoolYear::orderBy('name', 'desc')->get(),
            'gradeLevels' => GradeLevel::orderBy('order')->get()
        ]);
    }
}
