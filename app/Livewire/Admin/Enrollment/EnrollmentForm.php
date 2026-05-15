<?php

namespace App\Livewire\Admin\Enrollment;

use App\Models\Enrollment;
use App\Models\GradeLevel;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Student;
use Livewire\Component;

class EnrollmentForm extends Component
{
    // Search
    public string $search = '';
    public ?Student $selectedStudent = null;

    // Form fields
    public ?int $school_year_id = null;
    public ?int $grade_level_id = null;
    public ?int $section_id = null;
    public string $enrolled_at = '';

    // Data
    public $schoolYears = [];
    public $gradeLevels = [];
    public $sections = [];
    public $searchResults = [];

    public function mount(): void
    {
        $this->schoolYears = SchoolYear::all();
        $this->gradeLevels = GradeLevel::orderBy('order')->get();
        $this->enrolled_at = now()->toDateString();

        // Auto-select active school year
        $activeYear = SchoolYear::where('is_active', true)->first();
        if ($activeYear) {
            $this->school_year_id = $activeYear->id;
        }
    }

    public function updatedSearch(): void
    {
        if (strlen($this->search) < 2) {
            $this->searchResults = [];
            return;
        }

        $this->searchResults = Student::where('status', 'active')
            ->where(function ($query) {
                $query->where('first_name', 'like', "%{$this->search}%")
                    ->orWhere('last_name', 'like', "%{$this->search}%")
                    ->orWhere('lrn', 'like', "%{$this->search}%");
            })
            ->limit(5)
            ->get();
    }

    public function selectStudent(int $studentId): void
    {
        $this->selectedStudent = Student::find($studentId);
        $this->search = '';
        $this->searchResults = [];
    }

    public function updatedGradeLevelId(): void
    {
        $this->sections = Section::where('grade_level_id', $this->grade_level_id)->get();
        $this->section_id = null;
    }

    public function enroll(): void
    {
        $this->validate([
            'selectedStudent' => 'required',
            'school_year_id'  => 'required|exists:school_years,id',
            'grade_level_id'  => 'required|exists:grade_levels,id',
            'section_id'      => 'required|exists:sections,id',
            'enrolled_at'     => 'required|date',
        ]);

        // Check if already enrolled this school year
        $exists = Enrollment::where('student_id', $this->selectedStudent->id)
            ->where('school_year_id', $this->school_year_id)
            ->exists();

        if ($exists) {
            $this->addError('selectedStudent', 'Student is already enrolled this school year.');
            return;
        }

        Enrollment::create([
            'student_id'     => $this->selectedStudent->id,
            'school_year_id' => $this->school_year_id,
            'grade_level_id' => $this->grade_level_id,
            'section_id'     => $this->section_id,
            'status'         => 'enrolled',
            'enrolled_at'    => $this->enrolled_at,
        ]);

        session()->flash('success', 'Student enrolled successfully.');
        $this->reset(['selectedStudent', 'grade_level_id', 'section_id', 'search']);
    }

    public function render()
    {
        return view('livewire.admin.enrollment.enrollment-form');
    }
}