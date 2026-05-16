<?php

use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Teacher\Dashboard as TeacherDashboard;
use App\Livewire\Student\Dashboard as StudentDashboard;
use App\Livewire\Parent\Dashboard as ParentDashboard;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Enrollment\EnrollmentForm;
use App\Livewire\Admin\Students\StudentList;
use App\Livewire\Admin\Students\StudentProfile;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::middleware(['auth', 'role:superadmin,admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('/enrollment/create', EnrollmentForm::class)->name('enrollment.create');
    Route::get('/students', StudentList::class)->name('students.index');
    Route::get('/students/{student}', StudentProfile::class)->name('students.show');
});

Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', TeacherDashboard::class)->name('dashboard');
});

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', StudentDashboard::class)->name('dashboard');
});

Route::middleware(['auth', 'role:parent'])->prefix('parent')->name('parent.')->group(function () {
    Route::get('/dashboard', ParentDashboard::class)->name('dashboard');
});

require __DIR__.'/settings.php';