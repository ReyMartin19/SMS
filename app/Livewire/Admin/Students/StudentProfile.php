<?php

namespace App\Livewire\Admin\Students;

use App\Models\ActivityLog;
use App\Models\Student;
use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Student Profile')]
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
        if (!in_array(auth()->user()->role, ['superadmin', 'admin'])) {
            abort(403, 'Access denied.');
        }

        $this->student = $student->load([
            'enrollments.gradeLevel',
            'enrollments.section',
            'enrollments.schoolYear',
            'user',
        ]);
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
        $this->first_name = trim(strip_tags($this->first_name));
        $this->middle_name = trim(strip_tags($this->middle_name));
        $this->last_name = trim(strip_tags($this->last_name));
        $this->suffix = trim(strip_tags($this->suffix));
        $this->birthplace = trim(strip_tags($this->birthplace));
        $this->address = trim(strip_tags($this->address));
        $this->contact_number = trim(strip_tags($this->contact_number));
        $this->guardian_name = trim(strip_tags($this->guardian_name));
        $this->guardian_relationship = trim(strip_tags($this->guardian_relationship));
        $this->guardian_contact = trim(strip_tags($this->guardian_contact));
        $this->lrn = trim(strip_tags($this->lrn));

        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'gender'     => 'required|in:male,female',
            'birthdate'  => 'required|date',
            'address'    => 'required|string',
            'lrn'        => 'nullable|string|unique:students,lrn,' . $this->student->id,
            'status'     => 'required|in:active,graduated,dropped,transferred',
        ]);

        $oldValues = $this->student->only([
            'first_name', 'middle_name', 'last_name', 'suffix',
            'gender', 'birthdate', 'birthplace', 'address',
            'contact_number', 'guardian_name', 'guardian_relationship',
            'guardian_contact', 'lrn', 'status',
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

        $newValues = $this->student->fresh()->only(array_keys($oldValues));

        ActivityLog::log(
            'updated_student', 'student',
            "Updated student profile for {$this->student->first_name} {$this->student->last_name}",
            [
                'subject_type' => Student::class,
                'subject_id'   => $this->student->id,
                'old_values'   => $oldValues,
                'new_values'   => $newValues,
            ]
        );

        $this->student->refresh();
        $this->editing = false;

        session()->flash('success', 'Student updated successfully.');
    }

    public function createAccount(): void
    {
        if ($this->student->user_id) {
            return;
        }

        if ($this->student->lrn) {
            $email = $this->student->lrn . '@school.com';
        } else {
            $firstName = strtolower(str_replace(' ', '', $this->student->first_name));
            $lastName  = strtolower(str_replace(' ', '', $this->student->last_name));
            $email     = $firstName . '.' . $lastName . '@school.com';
        }

        // Ensure email uniqueness
        $baseEmail = $email;
        $counter   = 1;
        while (User::where('email', $email)->exists()) {
            $email = str_replace('@', $counter . '@', $baseEmail);
            $counter++;
        }

        $password = \Carbon\Carbon::parse($this->student->birthdate)->format('Ymd');

        $user = User::create([
            'name'                  => $this->student->first_name . ' ' . $this->student->last_name,
            'email'                 => $email,
            'password'              => bcrypt($password),
            'role'                  => 'student',
            'force_password_change' => true,
        ]);

        $this->student->update(['user_id' => $user->id]);
        $this->student->refresh()->load('user');

        ActivityLog::log(
            'created_portal_account', 'accounts',
            "Created portal account for {$this->student->first_name} {$this->student->last_name} — Email: {$email}",
            [
                'subject_type' => Student::class,
                'subject_id'   => $this->student->id,
            ]
        );

        session()->flash('success',
            'Portal account created — Email: ' . $email . ' | Password: ' . $password
        );
    }

    public function render()
    {
        return view('livewire.admin.students.student-profile');
    }
}