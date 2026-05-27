<?php

namespace App\Livewire\Teacher;

use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Teacher;
use App\Models\SchoolYear;
use App\Models\TeacherAssignment;
use App\Models\Enrollment;
use App\Models\StudentGrade;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

#[Title('Teacher Dashboard')]
class Dashboard extends Component
{
    public ?Teacher $teacher = null;
    public ?SchoolYear $activeSchoolYear = null;
    public array $expandedAnnouncements = [];

    public function mount()
    {
        $this->teacher = Teacher::where('user_id', Auth::id())->first();
        $this->activeSchoolYear = SchoolYear::where('is_active', true)->first();
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
        $stats = [
            'totalSubjects' => 0,
            'totalSections' => 0,
            'totalStudents' => 0,
            'totalGradesEntered' => 0,
        ];

        $assignments = collect();
        $recentGrades = collect();
        $announcements = collect();

        if ($this->teacher && $this->activeSchoolYear) {
            // Fetch assignments for current active school year
            $assignments = TeacherAssignment::with(['subject', 'section', 'section.gradeLevel'])
                ->where('teacher_id', $this->teacher->id)
                ->where('school_year_id', $this->activeSchoolYear->id)
                ->get();

            // Calculate student count for each assignment
            foreach ($assignments as $assignment) {
                $assignment->student_count = Enrollment::where('section_id', $assignment->section_id)
                    ->where('school_year_id', $this->activeSchoolYear->id)
                    ->where('status', 'enrolled')
                    ->count();
            }

            // Stats computations
            $stats['totalSubjects'] = $assignments->pluck('subject_id')->unique()->count();
            $stats['totalSections'] = $assignments->pluck('section_id')->unique()->count();
            
            $sectionIds = $assignments->pluck('section_id')->unique()->toArray();
            $stats['totalStudents'] = Enrollment::whereIn('section_id', $sectionIds)
                ->where('school_year_id', $this->activeSchoolYear->id)
                ->where('status', 'enrolled')
                ->distinct('student_id')
                ->count('student_id');

            if ($assignments->isNotEmpty()) {
                $stats['totalGradesEntered'] = StudentGrade::where('school_year_id', $this->activeSchoolYear->id)
                    ->whereNotNull('quarter_grade')
                    ->where(function ($query) use ($assignments) {
                        foreach ($assignments as $assignment) {
                            $query->orWhere(function ($q) use ($assignment) {
                                $q->where('subject_id', $assignment->subject_id)
                                  ->where('section_id', $assignment->section_id);
                            });
                        }
                    })
                    ->count();

                // Fetch 5 most recent grade entries
                $recentGrades = StudentGrade::with(['student', 'subject', 'section'])
                    ->where('school_year_id', $this->activeSchoolYear->id)
                    ->whereNotNull('quarter_grade')
                    ->where(function ($query) use ($assignments) {
                        foreach ($assignments as $assignment) {
                            $query->orWhere(function ($q) use ($assignment) {
                                $q->where('subject_id', $assignment->subject_id)
                                  ->where('section_id', $assignment->section_id);
                            });
                        }
                    })
                    ->orderBy('updated_at', 'desc')
                    ->limit(5)
                    ->get();
            }

            // Fetch Announcements
            $announcements = Announcement::visible('teacher')
                ->orderBy('is_pinned', 'desc')
                ->orderBy('published_at', 'desc')
                ->take(5)
                ->get();
        }

        return view('livewire.teacher.dashboard', [
            'stats' => $stats,
            'assignments' => $assignments,
            'recentGrades' => $recentGrades,
            'announcements' => $announcements,
        ]);
    }
}
