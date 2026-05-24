<?php

namespace App\Livewire\Admin\Teachers;

use App\Models\Teacher;
use Livewire\Component;

class TeacherForm extends Component
{
    public $first_name = '';
    public $middle_name = '';
    public $last_name = '';
    public $suffix = '';
    public $gender = '';
    public $birthdate = '';
    public $address = '';
    public $contact_number = '';
    public $employee_id = '';
    public $specialization = '';
    public $status = 'active';

    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:50',
            'gender' => 'required|in:male,female',
            'birthdate' => 'required|date',
            'address' => 'required|string',
            'contact_number' => 'nullable|string|max:50',
            'employee_id' => 'nullable|string|max:255|unique:teachers,employee_id',
            'specialization' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        Teacher::create($validated);

        session()->flash('success', 'Teacher added successfully.');
        return $this->redirectRoute('admin.teachers.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.teachers.teacher-form');
    }
}
