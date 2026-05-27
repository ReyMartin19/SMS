<?php

namespace App\Livewire\Admin\Teachers;

use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherAssignment;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Assignments')]
class AssignmentManager extends Component
{
    use WithPagination;

    public $schoolYearFilter = '';
    
    public $showForm = false;
    public $teacher_id = '';
    public $subject_id = '';
    public $section_id = '';
    public $school_year_id = '';

    public function mount()
    {
        $activeYear = SchoolYear::where('is_active', true)->first();
        if ($activeYear) {
            $this->schoolYearFilter = $activeYear->id;
        }
    }

    public function rules()
    {
        return [
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'section_id' => 'required|exists:sections,id',
            'school_year_id' => 'required|exists:school_years,id',
        ];
    }

    public function create()
    {
        $this->reset(['teacher_id', 'subject_id', 'section_id']);
        $this->school_year_id = $this->schoolYearFilter;
        $this->resetValidation();
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        // Business rule validation: One subject per teacher per school year
        $existing = TeacherAssignment::where('teacher_id', $this->teacher_id)
            ->where('school_year_id', $this->school_year_id)
            ->where('subject_id', '!=', $this->subject_id)
            ->first();

        if ($existing) {
            $this->addError('teacher_id', 'This teacher is already assigned to a different subject (' . $existing->subject->name . ') in this school year.');
            return;
        }
        
        // Also prevent exact duplicate assignments
        $duplicate = TeacherAssignment::where('teacher_id', $this->teacher_id)
            ->where('school_year_id', $this->school_year_id)
            ->where('subject_id', $this->subject_id)
            ->where('section_id', $this->section_id)
            ->first();

        if ($duplicate) {
            $this->addError('section_id', 'This teacher is already assigned to this section for this subject.');
            return;
        }

        TeacherAssignment::create([
            'teacher_id' => $this->teacher_id,
            'subject_id' => $this->subject_id,
            'section_id' => $this->section_id,
            'school_year_id' => $this->school_year_id,
        ]);

        $this->showForm = false;
        session()->flash('success', 'Assignment added successfully.');
    }

    public function delete($id)
    {
        TeacherAssignment::findOrFail($id)->delete();
        session()->flash('success', 'Assignment removed successfully.');
    }

    public function render()
    {
        $assignments = TeacherAssignment::with(['teacher', 'subject', 'section.gradeLevel', 'schoolYear'])
            ->when($this->schoolYearFilter, function ($query) {
                $query->where('school_year_id', $this->schoolYearFilter);
            })
            ->latest()
            ->paginate(15);

        return view('livewire.admin.teachers.assignment-manager', [
            'assignments' => $assignments,
            'teachers' => Teacher::where('status', 'active')->orderBy('last_name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
            'sections' => Section::with('gradeLevel')->get()->sortBy(function($section) {
                return $section->gradeLevel->order . '-' . $section->name;
            }),
            'schoolYears' => SchoolYear::orderByDesc('start_date')->get(),
        ]);
    }
}
