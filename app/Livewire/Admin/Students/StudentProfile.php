<?php

namespace App\Livewire\Admin\Students;

use App\Models\Student;
use Livewire\Component;

class StudentProfile extends Component
{
    public Student $student;

    // Edit mode
    public bool $editing = false;

    // Editable fields
    public string $first_name = '';
    public string $middle_name = '';
    public string $last_name = '';
    public string $suffix = '';
    public string $gender = '';
    public string $birthdate = '';
    public string $birthplace = '';
    public string $address = '';
    public string $contact_number = '';
    public string $guardian_name = '';
    public string $guardian_relationship = '';
    public string $guardian_contact = '';
    public string $lrn = '';
    public string $status = '';

    public function mount(Student $student): void
    {
        $this->student = $student->load(['enrollments.gradeLevel', 'enrollments.section', 'enrollments.schoolYear']);
        $this->fillFields();
    }

    public function fillFields(): void
    {
        $this->first_name           = $this->student->first_name;
        $this->middle_name          = $this->student->middle_name ?? '';
        $this->last_name            = $this->student->last_name;
        $this->suffix               = $this->student->suffix ?? '';
        $this->gender               = $this->student->gender;
        $this->birthdate = \Carbon\Carbon::parse($this->student->birthdate)->format('Y-m-d');
        $this->birthplace           = $this->student->birthplace ?? '';
        $this->address              = $this->student->address;
        $this->contact_number       = $this->student->contact_number ?? '';
        $this->guardian_name        = $this->student->guardian_name ?? '';
        $this->guardian_relationship = $this->student->guardian_relationship ?? '';
        $this->guardian_contact     = $this->student->guardian_contact ?? '';
        $this->lrn                  = $this->student->lrn ?? '';
        $this->status               = $this->student->status;
    }

    public function edit(): void
    {
        $this->editing = true;
    }

    public function cancelEdit(): void
    {
        $this->editing = false;
        $this->fillFields();
    }

    public function save(): void
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'gender'     => 'required|in:male,female',
            'birthdate'  => 'required|date',
            'address'    => 'required|string',
            'lrn'        => 'nullable|string|unique:students,lrn,' . $this->student->id,
            'status'     => 'required|in:active,graduated,dropped,transferred',
        ]);

        $this->student->update([
            'first_name'             => $this->first_name,
            'middle_name'            => $this->middle_name,
            'last_name'              => $this->last_name,
            'suffix'                 => $this->suffix,
            'gender'                 => $this->gender,
            'birthdate'              => $this->birthdate,
            'birthplace'             => $this->birthplace,
            'address'                => $this->address,
            'contact_number'         => $this->contact_number,
            'guardian_name'          => $this->guardian_name,
            'guardian_relationship'  => $this->guardian_relationship,
            'guardian_contact'       => $this->guardian_contact,
            'lrn'                    => $this->lrn ?: null,
            'status'                 => $this->status,
        ]);

        $this->student->refresh();
        $this->editing = false;

        session()->flash('success', 'Student updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.students.student-profile');
    }
}