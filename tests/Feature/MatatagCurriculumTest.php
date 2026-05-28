<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\GradeLevel;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Subject;
use App\Models\StudentSubjectEnrollment;
use Database\Seeders\GradeLevelSeeder;
use Database\Seeders\SubjectSeeder;
use Database\Seeders\SchoolYearSeeder;
use Database\Seeders\SectionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Livewire\Admin\Students\StudentProfile;

uses(RefreshDatabase::class);

test('subject seeder creates subjects idempotently', function () {
    $this->seed(GradeLevelSeeder::class);
    $this->seed(SubjectSeeder::class);

    $subjectCount = Subject::count();
    expect($subjectCount)->toBeGreaterThan(0);

    // Seed again
    $this->seed(SubjectSeeder::class);
    expect(Subject::count())->toBe($subjectCount);
});

test('admin can change track of a grade 12 student on profile', function () {
    // Seed required lookups
    $this->seed(GradeLevelSeeder::class);
    $this->seed(SubjectSeeder::class);
    $this->seed(SchoolYearSeeder::class);
    $this->seed(SectionSeeder::class);

    // Create an admin user
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@school.com',
        'password' => bcrypt('password123'),
        'role' => 'admin',
    ]);

    // Create a Grade 12 student
    $student = Student::create([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'gender' => 'male',
        'birthdate' => '2008-01-01',
        'address' => '123 Main St',
        'status' => 'active',
    ]);

    $schoolYear = SchoolYear::first();
    $grade12 = GradeLevel::where('order', 12)->first();
    $section = Section::where('grade_level_id', $grade12->id)->first();

    // Enroll student in Grade 12 STEM initially
    $enrollment = Enrollment::create([
        'student_id' => $student->id,
        'school_year_id' => $schoolYear->id,
        'grade_level_id' => $grade12->id,
        'section_id' => $section->id,
        'track' => 'stem',
        'status' => 'enrolled',
        'enrolled_at' => now()->toDateString(),
    ]);

    // Assign initial stem subjects
    $service = new \App\Services\SubjectAssignmentService();
    $service->assignSubjects($student, $enrollment, $grade12, 'stem');

    // Confirm initial stem subjects are assigned
    $initialSubjects = StudentSubjectEnrollment::where('student_id', $student->id)->get();
    expect($initialSubjects->count())->toBe(6); // 6 STEM subjects
    foreach ($initialSubjects as $sse) {
        expect($sse->subject->track)->toBe('stem');
    }

    // Act as admin and change track to ABM
    $this->actingAs($admin);

    Livewire::test(StudentProfile::class, ['student' => $student])
        ->set('selectedTrack', 'abm')
        ->call('saveTrack')
        ->assertHasNoErrors();

    // Check enrollment track updated
    expect($enrollment->fresh()->track)->toBe('abm');

    // Check subjects are updated to ABM subjects
    $updatedSubjects = StudentSubjectEnrollment::where('student_id', $student->id)->get();
    expect($updatedSubjects->count())->toBe(6); // 6 ABM subjects
    foreach ($updatedSubjects as $sse) {
        expect($sse->subject->track)->toBe('abm');
    }
});
