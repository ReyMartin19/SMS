<?php

namespace App\Livewire\Admin\Reports;

use App\Models\SchoolYear;
use App\Models\GradeLevel;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Student;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Report Center')]
class ReportCenter extends Component
{
    // Enrollment Report Filters
    public $enrollmentSchoolYearId = '';
    public $enrollmentGradeLevelId = '';
    public $enrollmentSectionId = '';
    public $enrollmentStatus = '';

    // Student List Filters
    public $studentStatus = '';
    public $studentGender = '';
    public $studentGradeLevelId = '';

    // Grades Report Filters
    public $gradesSchoolYearId = '';
    public $gradesSectionId = '';
    public $gradesSubjectId = '';
    public $gradesQuarter = '';

    // Student Search for Report Card
    public $studentSearch = '';

    public function mount()
    {
        $activeSy = SchoolYear::where('is_active', true)->first() ?? SchoolYear::latest()->first();
        if ($activeSy) {
            $this->enrollmentSchoolYearId = $activeSy->id;
            $this->gradesSchoolYearId = $activeSy->id;
        }
    }

    // Reset sections if grade level changes
    public function updatedEnrollmentGradeLevelId()
    {
        $this->enrollmentSectionId = '';
    }

    public function getSchoolYearsProperty()
    {
        return SchoolYear::orderBy('name', 'desc')->get();
    }

    public function getGradeLevelsProperty()
    {
        return GradeLevel::orderBy('order', 'asc')->get();
    }

    public function getEnrollmentSectionsProperty()
    {
        $query = Section::query();
        if ($this->enrollmentGradeLevelId) {
            $query->where('grade_level_id', $this->enrollmentGradeLevelId);
        }
        return $query->orderBy('name', 'asc')->get();
    }

    public function getGradesSectionsProperty()
    {
        return Section::orderBy('name', 'asc')->get();
    }

    public function getSubjectsProperty()
    {
        $query = Subject::query();
        // If a section is selected, we can optionally filter by its grade level's subjects
        if ($this->gradesSectionId) {
            $section = Section::find($this->gradesSectionId);
            if ($section) {
                $query->where('grade_level_id', $section->grade_level_id);
            }
        }
        return $query->orderBy('name', 'asc')->get();
    }

    public function getSearchedStudentsProperty()
    {
        if (strlen(trim($this->studentSearch)) < 2) {
            return [];
        }

        return Student::query()
            ->where(function ($q) {
                $q->where('first_name', 'like', "%{$this->studentSearch}%")
                  ->orWhere('last_name', 'like', "%{$this->studentSearch}%")
                  ->orWhere('lrn', 'like', "%{$this->studentSearch}%");
            })
            ->limit(8)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.reports.report-center', [
            'schoolYears' => $this->schoolYears,
            'gradeLevels' => $this->gradeLevels,
            'enrollmentSections' => $this->enrollmentSections,
            'gradesSections' => $this->gradesSections,
            'subjects' => $this->subjects,
            'searchedStudents' => $this->searchedStudents,
        ]);
    }
}
