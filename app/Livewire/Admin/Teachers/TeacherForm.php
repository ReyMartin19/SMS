<?php

namespace App\Livewire\Admin\Teachers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
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
    public $email = '';

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
            'email' => 'required|email|max:255|unique:users,email',
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        // Create the user account for the teacher
        $user = User::create([
            'name' => trim($this->first_name . ' ' . $this->last_name),
            'email' => $this->email,
            'password' => Hash::make($this->employee_id ?: 'password'),
            'role' => 'teacher',
        ]);

        $validated['user_id'] = $user->id;
        unset($validated['email']);

        Teacher::create($validated);

        session()->flash('success', 'Teacher added successfully.');
        return $this->redirectRoute('admin.teachers.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.teachers.teacher-form');
    }
}
