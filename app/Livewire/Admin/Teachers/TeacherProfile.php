<?php

namespace App\Livewire\Admin\Teachers;

use App\Models\Teacher;
use Livewire\Component;

class TeacherProfile extends Component
{
    public Teacher $teacher;

    public function mount(Teacher $teacher)
    {
        $this->teacher = $teacher;
    }

    public function render()
    {
        $assignments = $this->teacher->assignments()->with(['subject', 'section.gradeLevel', 'schoolYear'])->get();
        return view('livewire.admin.teachers.teacher-profile', [
            'assignments' => $assignments,
        ]);
    }
}
