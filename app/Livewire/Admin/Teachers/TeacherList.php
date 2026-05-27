<?php

namespace App\Livewire\Admin\Teachers;

use App\Models\Teacher;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Teachers')]
class TeacherList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    public function delete($id)
    {
        Teacher::findOrFail($id)->delete();
        session()->flash('success', 'Teacher deleted successfully.');
    }

    public function render()
    {
        $teachers = Teacher::with('user')
            ->when($this->search, function ($query) {
                $query->where(function($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhere('employee_id', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->paginate(10);

        return view('livewire.admin.teachers.teacher-list', [
            'teachers' => $teachers,
        ]);
    }
}
