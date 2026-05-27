<?php

namespace App\Livewire\Student;

use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Student;
use App\Models\SchoolYear;
use App\Models\Enrollment;
use App\Models\Subject;
use App\Models\StudentGrade;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

#[Title('My Dashboard')]
class Dashboard extends Component
{
    public ?Student $student = null;
    public ?SchoolYear $activeSchoolYear = null;
    public ?Enrollment $enrollment = null;
    public int $quarter = 1;
    public array $expandedAnnouncements = [];

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

    public function setQuarter(int $q)
    {
        if (in_array($q, [1, 2, 3, 4])) {
            $this->quarter = $q;
        }
    }

    public function toggleAnnouncement(int $id)
    {
        if (in_array($id, $this->expandedAnnouncements)) {
            $this->expandedAnnouncements = array_diff($this->expandedAnnouncements, [$id]);
        } else {
            $this->expandedAnnouncements[] = $id;
        }
    }

    public function render()
    {
        $subjects = collect();
        $grades = collect();
        $announcements = collect();

        if ($this->student && $this->activeSchoolYear) {
            // Get announcements
            $announcements = Announcement::visible('student')
                ->orderBy('is_pinned', 'desc')
                ->orderBy('published_at', 'desc')
                ->take(5)
                ->get();

            if ($this->enrollment) {
                // Fetch subjects assigned to student's section
                $subjects = Subject::whereIn('id', function ($query) {
                    $query->select('subject_id')
                        ->from('teacher_assignments')
                        ->where('section_id', $this->enrollment->section_id)
                        ->where('school_year_id', $this->activeSchoolYear->id);
                })->get();

                // Fetch grades entered for student in this quarter
                $grades = StudentGrade::where('student_id', $this->student->id)
                    ->where('school_year_id', $this->activeSchoolYear->id)
                    ->where('quarter', $this->quarter)
                    ->get()
                    ->keyBy('subject_id');
            }
        }

        return view('livewire.student.dashboard', [
            'subjects' => $subjects,
            'grades' => $grades,
            'announcements' => $announcements,
        ]);
    }
}
