<?php

use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Teacher\Dashboard as TeacherDashboard;
use App\Livewire\Student\Dashboard as StudentDashboard;
use App\Livewire\Parent\Dashboard as ParentDashboard;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Enrollment\EnrollmentForm;
use App\Livewire\Admin\Students\StudentList;
use App\Livewire\Admin\Students\StudentProfile;
use App\Livewire\Admin\Academic\SchoolYearManager;
use App\Livewire\Admin\Academic\GradeLevelManager;
use App\Livewire\Admin\Academic\SectionManager;

use App\Livewire\Admin\Teachers\TeacherList;
use App\Livewire\Admin\Teachers\TeacherProfile;
use App\Livewire\Admin\Teachers\TeacherForm;
use App\Livewire\Admin\Teachers\SubjectManager;
use App\Livewire\Admin\Teachers\AssignmentManager;
use App\Livewire\Admin\Logs\ActivityLogViewer;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('password/change', \App\Livewire\Auth\ChangePassword::class)->name('password.change');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return match(auth()->user()->role) {
            'superadmin', 'admin' => redirect()->route('admin.dashboard'),
            'teacher'             => redirect()->route('teacher.dashboard'),
            'student'             => redirect()->route('student.dashboard'),
            'parent'              => redirect()->route('parent.dashboard'),
            default               => redirect()->route('home'),
        };
    })->name('dashboard');
});

Route::middleware(['auth', 'role:superadmin,admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('/enrollment/create', EnrollmentForm::class)->name('enrollment.create');
    Route::get('/students', StudentList::class)->name('students.index');
    Route::get('/students/{student}', StudentProfile::class)->name('students.show');
    Route::get('/academic/school-years', SchoolYearManager::class)->name('academic.school-years');
    Route::get('/academic/grade-levels', GradeLevelManager::class)->name('academic.grade-levels');
    Route::get('/academic/sections', SectionManager::class)->name('academic.sections');
    
    Route::get('/teachers', TeacherList::class)->name('teachers.index');
    Route::get('/teachers/create', TeacherForm::class)->name('teachers.create');
    Route::get('/teachers/{teacher}', TeacherProfile::class)->name('teachers.show');
    Route::get('/subjects', SubjectManager::class)->name('subjects.index');
    Route::get('/assignments', AssignmentManager::class)->name('assignments.index');
    
    Route::get('/grades', \App\Livewire\Admin\Grades\GradeOverview::class)->name('grades.index');
    Route::get('/grades/report-card', \App\Livewire\Admin\Grades\ReportCard::class)->name('grades.report-card');
    Route::get('/announcements', \App\Livewire\Admin\Announcements\AnnouncementManager::class)->name('announcements.index');

    // Reports Module
    Route::get('/reports', \App\Livewire\Admin\Reports\ReportCenter::class)->name('reports.index');
    Route::get('/reports/report-card', \App\Livewire\Admin\Reports\ReportCard::class)->name('reports.report-card');
    Route::get('/reports/pdf/enrollment', [\App\Http\Controllers\ReportController::class, 'downloadEnrollmentPdf'])->name('reports.pdf.enrollment');
    Route::get('/reports/pdf/students', [\App\Http\Controllers\ReportController::class, 'downloadStudentListPdf'])->name('reports.pdf.students');
    Route::get('/reports/pdf/report-card/{student}', [\App\Http\Controllers\ReportController::class, 'downloadReportCardPdf'])->name('reports.pdf.report-card');
    Route::get('/reports/excel/enrollment', [\App\Http\Controllers\ReportController::class, 'exportEnrollmentExcel'])->name('reports.excel.enrollment');
    Route::get('/reports/excel/students', [\App\Http\Controllers\ReportController::class, 'exportStudentListExcel'])->name('reports.excel.students');
    Route::get('/reports/excel/grades', [\App\Http\Controllers\ReportController::class, 'exportGradesExcel'])->name('reports.excel.grades');

    // System Settings (superadmin only)
    Route::middleware(['role:superadmin'])->group(function () {
        Route::get('/settings/system', \App\Livewire\Admin\Settings\SystemSettingsManager::class)->name('settings.system');
        Route::get('/logs', ActivityLogViewer::class)->name('logs.index');
    });
});

Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', TeacherDashboard::class)->name('dashboard');
    Route::get('/grades', \App\Livewire\Teacher\Grades\GradeEntry::class)->name('grades.entry');
    Route::get('/announcements', \App\Livewire\Shared\AnnouncementFeed::class)->name('announcements.index');
});

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', StudentDashboard::class)->name('dashboard');
    Route::get('/grades', \App\Livewire\Student\Grades\GradeViewer::class)->name('grades.index');
    Route::get('/announcements', \App\Livewire\Student\Announcements\AnnouncementFeed::class)->name('announcements.index');
});

Route::middleware(['auth', 'role:parent'])->prefix('parent')->name('parent.')->group(function () {
    Route::get('/dashboard', ParentDashboard::class)->name('dashboard');
    Route::get('/grades', \App\Livewire\Parent\Grades\GradeViewer::class)->name('grades.index');
    Route::get('/announcements', \App\Livewire\Parent\Announcements\AnnouncementFeed::class)->name('announcements.index');
});

require __DIR__.'/settings.php';