<?php

namespace App\Livewire\Admin\Enrollment;

use App\Models\ActivityLog;
use App\Models\Enrollment;
use App\Models\GradeLevel;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Enroll Student')]
class EnrollmentForm extends Component
{

    // New student form toggle
    public bool $showStudentForm = false;

    // New student fields
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

    // Search
    public string $search = '';
    public ?Student $selectedStudent = null;

    // Form fields
    public ?int $school_year_id = null;
    public ?int $grade_level_id = null;
    public ?int $section_id = null;
    public string $enrolled_at = '';
    public string $track = '';
    public bool $isGrade12 = false;

    // Data
    public $schoolYears = [];
    public $gradeLevels = [];
    public $sections = [];
    public $searchResults = [];

    public function mount(): void
    {
        if (!in_array(auth()->user()->role, ['superadmin', 'admin'])) {
            abort(403, 'Access denied.');
        }

        $this->schoolYears = SchoolYear::all();
        $this->gradeLevels = GradeLevel::orderBy('order')->get();
        $this->enrolled_at = now()->toDateString();

        // Auto-select active school year
        $activeYear = SchoolYear::where('is_active', true)->first();
        if ($activeYear) {
            $this->school_year_id = $activeYear->id;
        }
    }

    public function updatedSearch(): void
    {
        if (strlen($this->search) < 2) {
            $this->searchResults = [];
            return;
        }

        $this->searchResults = Student::where('status', 'active')
            ->where(function ($query) {
                $query->where('first_name', 'like', "%{$this->search}%")
                    ->orWhere('last_name', 'like', "%{$this->search}%")
                    ->orWhere('lrn', 'like', "%{$this->search}%");
            })
            ->limit(5)
            ->get();
    }

    public function selectStudent(int $studentId): void
    {
        $this->selectedStudent = Student::find($studentId);
        $this->search = '';
        $this->searchResults = [];
    }

    public function updatedGradeLevelId(): void
    {
        $this->sections = Section::where('grade_level_id', $this->grade_level_id)->get();
        $this->section_id = null;
        $this->track = '';

        $level = GradeLevel::find($this->grade_level_id);
        $this->isGrade12 = $level && $level->order === 12;
    }

    public function enroll(): void
    {
        $validationRules = [
            'selectedStudent' => 'required',
            'school_year_id'  => 'required|exists:school_years,id',
            'grade_level_id'  => 'required|exists:grade_levels,id',
            'section_id'      => 'required|exists:sections,id',
            'enrolled_at'     => 'required|date',
        ];

        if ($this->isGrade12) {
            $validationRules['track'] = 'required|in:stem,abm,humss,sports';
        }

        $this->validate($validationRules);

        // Check if already enrolled this school year
        $exists = Enrollment::where('student_id', $this->selectedStudent->id)
            ->where('school_year_id', $this->school_year_id)
            ->exists();

        if ($exists) {
            $this->addError('selectedStudent', 'Student is already enrolled this school year.');
            return;
        }

        $enrollment = Enrollment::create([
            'student_id'     => $this->selectedStudent->id,
            'school_year_id' => $this->school_year_id,
            'grade_level_id' => $this->grade_level_id,
            'section_id'     => $this->section_id,
            'status'         => 'enrolled',
            'enrolled_at'    => $this->enrolled_at,
            'track'          => $this->isGrade12 ? $this->track : null,
        ]);

        $service = new \App\Services\SubjectAssignmentService();
        $gradeLevel = GradeLevel::find($this->grade_level_id);
        $service->assignSubjects(
            $this->selectedStudent,
            $enrollment,
            $gradeLevel,
            $this->isGrade12 ? $this->track : null
        );

        $credentials = $this->createStudentAccount($this->selectedStudent);

        $section    = Section::find($this->section_id);

        ActivityLog::log(
            'enrolled_student', 'enrollment',
            "Enrolled {$this->selectedStudent->first_name} {$this->selectedStudent->last_name} to {$gradeLevel->name} - {$section->name}",
            [
                'subject_type' => Student::class,
                'subject_id'   => $this->selectedStudent->id,
                'new_values'   => [
                    'school_year_id' => $this->school_year_id,
                    'grade_level_id' => $this->grade_level_id,
                    'section_id'     => $this->section_id,
                    'track'          => $this->isGrade12 ? $this->track : null,
                ],
            ]
        );

        $message = 'Student enrolled successfully. ';
        if ($credentials) {
            $message .= 'Portal account created — Email: ' . $credentials['email'] . ' | Password: ' . $credentials['password'];
        } else {
            $message .= 'Student already has a portal account.';
        }

        session()->flash('success', $message);
        $this->reset(['selectedStudent', 'grade_level_id', 'section_id', 'search', 'track', 'isGrade12']);
    }

    /**
     * Generate a portal account for a student if they don't have one.
     * Returns ['email' => ..., 'password' => ...] on creation, or null if already existed.
     */
    private function createStudentAccount(Student $student): ?array
    {
        if ($student->user_id) {
            return null;
        }

        // Generate email
        if ($student->lrn) {
            $email = $student->lrn . '@school.com';
        } else {
            $firstName = strtolower(str_replace(' ', '', $student->first_name));
            $lastName  = strtolower(str_replace(' ', '', $student->last_name));
            $email     = $firstName . '.' . $lastName . '@school.com';
        }

        // Ensure email uniqueness
        $baseEmail = $email;
        $counter   = 1;
        while (User::where('email', $email)->exists()) {
            $email = str_replace('@', $counter . '@', $baseEmail);
            $counter++;
        }

        // Password: birthdate in Ymd format
        $password = \Carbon\Carbon::parse($student->birthdate)->format('Ymd');

        $user = User::create([
            'name'                  => $student->first_name . ' ' . $student->last_name,
            'email'                 => $email,
            'password'              => bcrypt($password),
            'role'                  => 'student',
            'force_password_change' => true,
        ]);

        $student->update(['user_id' => $user->id]);

        return ['email' => $email, 'password' => $password];
    }

    public function showCreateStudent(): void
    {
        $this->showStudentForm = true;
    }

    public function cancelCreateStudent(): void
    {
        $this->showStudentForm = false;
        $this->reset([
            'first_name', 'middle_name', 'last_name', 'suffix',
            'gender', 'birthdate', 'birthplace', 'address',
            'contact_number', 'guardian_name', 'guardian_relationship',
            'guardian_contact', 'lrn',
        ]);
    }

    public function saveStudent(): void
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
            'lrn'        => 'nullable|string|unique:students,lrn',
        ]);

        $student = Student::create([
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
            'status'                 => 'active',
        ]);

        ActivityLog::log(
            'created_student', 'student',
            "Created student record for {$student->first_name} {$student->last_name}",
            [
                'subject_type' => Student::class,
                'subject_id'   => $student->id,
                'new_values'   => $student->toArray(),
            ]
        );

        $this->selectedStudent = $student;
        $this->showStudentForm = false;
        $this->cancelCreateStudent();
    }

    public function clearStudent(): void
    {
        $this->selectedStudent = null;
    }

    public function render()
    {
        return view('livewire.admin.enrollment.enrollment-form');
    }
}