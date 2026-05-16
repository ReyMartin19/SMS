<?php

namespace App\Livewire\Admin\Students;

use App\Models\Enrollment;
use App\Models\GradeLevel;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Student;
use Livewire\Component;
use Livewire\WithPagination;

class StudentList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public ?int $gradeLevelFilter = null;
    public ?int $sectionFilter = null;
    public ?int $schoolYearFilter = null;

    public $gradeLevels = [];
    public $sections = [];
    public $schoolYears = [];

    public function mount(): void
    {
        $this->gradeLevels = GradeLevel::orderBy('order')->get();
        $this->schoolYears = SchoolYear::orderByDesc('is_active')->get();

        $activeYear = SchoolYear::where('is_active', true)->first();
        if ($activeYear) {
            $this->schoolYearFilter = $activeYear->id;
        }
    }

    public function updatedGradeLevelFilter(): void
    {
        $this->sections = $this->gradeLevelFilter
            ? Section::where('grade_level_id', $this->gradeLevelFilter)->get()
            : [];
        $this->sectionFilter = null;
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedSectionFilter(): void
    {
        $this->resetPage();
    }

    public function updatedSchoolYearFilter(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->gradeLevelFilter = null;
        $this->sectionFilter = null;
        $activeYear = SchoolYear::where('is_active', true)->first();
        $this->schoolYearFilter = $activeYear?->id;
        $this->sections = [];
        $this->resetPage();
    }

    public function render()
    {
        $students = Student::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('first_name', 'like', "%{$this->search}%")
                      ->orWhere('last_name', 'like', "%{$this->search}%")
                      ->orWhere('lrn', 'like', "%{$this->search}%");
                });
            })
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->schoolYearFilter || $this->gradeLevelFilter || $this->sectionFilter, function ($query) {
                $query->whereHas('enrollments', function ($q) {
                    $q->when($this->schoolYearFilter, fn ($q) => $q->where('school_year_id', $this->schoolYearFilter))
                      ->when($this->gradeLevelFilter, fn ($q) => $q->where('grade_level_id', $this->gradeLevelFilter))
                      ->when($this->sectionFilter, fn ($q) => $q->where('section_id', $this->sectionFilter));
                });
            })
            ->with(['enrollments' => function ($q) {
                $q->with(['gradeLevel', 'section', 'schoolYear'])
                  ->when($this->schoolYearFilter, fn ($q) => $q->where('school_year_id', $this->schoolYearFilter));
            }])
            ->orderBy('last_name')
            ->paginate(15);

        return view('livewire.admin.students.student-list', compact('students'));
    }
}