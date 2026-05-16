<?php

namespace App\Livewire\Admin;

use App\Models\Enrollment;
use App\Models\GradeLevel;
use App\Models\SchoolYear;
use App\Models\Student;
use Livewire\Component;

class Dashboard extends Component
{
    public $totalStudents;
    public $enrolledThisYear;
    public $totalGradeLevels;
    public $activeSchoolYear;
    public $enrollmentsByGradeLevel;
    public $recentEnrollments;

    public function mount(): void
    {
        $activeYear = SchoolYear::where('is_active', true)->first();
        $this->activeSchoolYear = $activeYear;

        $this->totalStudents = Student::count();

        $this->enrolledThisYear = $activeYear
            ? Enrollment::where('school_year_id', $activeYear->id)
                        ->where('status', 'enrolled')
                        ->count()
            : 0;

        $this->totalGradeLevels = GradeLevel::count();

        $this->enrollmentsByGradeLevel = $activeYear
            ? GradeLevel::withCount(['enrollments' => function ($q) use ($activeYear) {
                $q->where('school_year_id', $activeYear->id)
                  ->where('status', 'enrolled');
            }])
            ->orderBy('order')
            ->get()
            : collect();

        $this->recentEnrollments = $activeYear
            ? Enrollment::with(['student', 'gradeLevel', 'section'])
                        ->where('school_year_id', $activeYear->id)
                        ->latest()
                        ->limit(5)
                        ->get()
            : collect();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}