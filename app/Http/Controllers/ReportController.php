<?php

namespace App\Http\Controllers;

use App\Exports\EnrollmentExport;
use App\Exports\StudentListExport;
use App\Exports\GradesExport;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Helper to stream standard CSV response fallback for Maatwebsite Excel
     */
    protected function streamCsv(string $filename, $export)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($export) {
            $stream = fopen('php://output', 'w');
            // Add UTF-8 BOM for proper Excel encoding
            fprintf($stream, chr(0xEF).chr(0xBB).chr(0xBF));
            $export->toCsv($stream);
            fclose($stream);
        }, 200, $headers);
    }

    /**
     * Export Enrollment report to CSV
     */
    public function exportEnrollmentExcel(Request $request)
    {
        $filters = $request->only(['school_year_id', 'grade_level_id', 'section_id', 'status']);
        $export = new EnrollmentExport($filters);
        $filename = 'enrollment_export_' . now()->format('YmdHis') . '.csv';

        return $this->streamCsv($filename, $export);
    }

    /**
     * Export Student List to CSV
     */
    public function exportStudentListExcel(Request $request)
    {
        $filters = $request->only(['status', 'gender', 'grade_level_id']);
        $export = new StudentListExport($filters);
        $filename = 'students_export_' . now()->format('YmdHis') . '.csv';

        return $this->streamCsv($filename, $export);
    }

    /**
     * Export Grades to CSV
     */
    public function exportGradesExcel(Request $request)
    {
        $filters = $request->only(['school_year_id', 'section_id', 'subject_id', 'quarter']);
        $export = new GradesExport($filters);
        $filename = 'grades_export_' . now()->format('YmdHis') . '.csv';

        return $this->streamCsv($filename, $export);
    }

    /**
     * PDF download for Enrollment report
     */
    public function downloadEnrollmentPdf(Request $request)
    {
        $filters = $request->only(['school_year_id', 'grade_level_id', 'section_id', 'status']);
        
        $query = \App\Models\Enrollment::with(['student', 'gradeLevel', 'section', 'schoolYear']);
        
        if ($request->school_year_id) {
            $query->where('school_year_id', $request->school_year_id);
            $filters['school_year_name'] = \App\Models\SchoolYear::find($request->school_year_id)?->name;
        }
        if ($request->grade_level_id) {
            $query->where('grade_level_id', $request->grade_level_id);
            $filters['grade_level_name'] = \App\Models\GradeLevel::find($request->grade_level_id)?->name;
        }
        if ($request->section_id) {
            $query->where('section_id', $request->section_id);
            $filters['section_name'] = \App\Models\Section::find($request->section_id)?->name;
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $enrollments = $query->orderBy('status', 'asc')->get();

        $pdf = Pdf::loadView('pdf.enrollment-report', compact('enrollments', 'filters'));
        return $pdf->download('enrollment_report_' . now()->format('YmdHis') . '.pdf');
    }

    /**
     * PDF download for Student List report
     */
    public function downloadStudentListPdf(Request $request)
    {
        $filters = $request->only(['status', 'gender', 'grade_level_id']);

        $query = \App\Models\Student::query();

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->gender) {
            $query->where('gender', $request->gender);
        }
        if ($request->grade_level_id) {
            $query->whereHas('enrollments', function ($q) use ($request) {
                $q->where('grade_level_id', $request->grade_level_id)
                  ->where('status', 'enrolled');
            });
            $filters['grade_level_name'] = \App\Models\GradeLevel::find($request->grade_level_id)?->name;
        }

        $students = $query->orderBy('last_name', 'asc')->get();

        $pdf = Pdf::loadView('pdf.student-list-report', compact('students', 'filters'));
        return $pdf->download('student_list_report_' . now()->format('YmdHis') . '.pdf');
    }

    /**
     * PDF download for Student Report Card
     */
    public function downloadReportCardPdf(Request $request, Student $student)
    {
        $schoolYearId = $request->query('school_year_id');
        if (!$schoolYearId) {
            $activeSy = \App\Models\SchoolYear::where('is_active', true)->first() ?? \App\Models\SchoolYear::latest()->first();
            $schoolYearId = $activeSy?->id;
        }

        $schoolYear = \App\Models\SchoolYear::find($schoolYearId);
        if (!$schoolYear) {
            abort(404, 'School year not found.');
        }

        $enrollment = \App\Models\Enrollment::where('student_id', $student->id)
            ->where('school_year_id', $schoolYearId)
            ->first();

        if (!$enrollment) {
            abort(404, 'Student is not enrolled in this school year.');
        }

        $gradeLevel = $enrollment->gradeLevel;
        $section = $enrollment->section;

        // Fetch subjects for this grade level
        $subjects = \App\Models\Subject::where('grade_level_id', $enrollment->grade_level_id)->get();

        // Fetch all student grades for this school year and student
        $grades = \App\Models\StudentGrade::where('student_id', $student->id)
            ->where('school_year_id', $schoolYearId)
            ->get();

        $gradesData = [];

        foreach ($subjects as $subject) {
            $q1 = $grades->where('subject_id', $subject->id)->where('quarter', 1)->first()?->quarter_grade;
            $q2 = $grades->where('subject_id', $subject->id)->where('quarter', 2)->first()?->quarter_grade;
            $q3 = $grades->where('subject_id', $subject->id)->where('quarter', 3)->first()?->quarter_grade;
            $q4 = $grades->where('subject_id', $subject->id)->where('quarter', 4)->first()?->quarter_grade;

            // Cast scores to float if not null
            $q1 = $q1 !== null ? (float)$q1 : null;
            $q2 = $q2 !== null ? (float)$q2 : null;
            $q3 = $q3 !== null ? (float)$q3 : null;
            $q4 = $q4 !== null ? (float)$q4 : null;

            // Calculate final grade: simple average of non-null quarters
            $activeGrades = array_filter([$q1, $q2, $q3, $q4], fn($v) => $v !== null);
            $finalGrade = count($activeGrades) > 0 ? (array_sum($activeGrades) / count($activeGrades)) : null;

            $remarks = null;
            if ($finalGrade !== null) {
                $remarks = $finalGrade >= 75 ? 'Passed' : 'Failed';
            }

            $gradesData[] = [
                'subject_id' => $subject->id,
                'subject_name' => $subject->name,
                'subject_code' => $subject->code,
                'q1' => $q1,
                'q2' => $q2,
                'q3' => $q3,
                'q4' => $q4,
                'final_grade' => $finalGrade,
                'remarks' => $remarks
            ];
        }

        // Calculate Overall Average
        $finalGrades = array_filter(array_column($gradesData, 'final_grade'), fn($v) => $v !== null);
        $overallAverage = count($finalGrades) > 0 ? array_sum($finalGrades) / count($finalGrades) : null;

        // Calculate Promotion Status
        $promotionStatus = 'Incomplete';
        if (!empty($gradesData) && $overallAverage !== null) {
            $hasFailedSubject = false;
            $hasGrades = false;
            foreach ($gradesData as $data) {
                if ($data['final_grade'] !== null) {
                    $hasGrades = true;
                    if ($data['final_grade'] < 75) {
                        $hasFailedSubject = true;
                    }
                }
            }
            if ($hasGrades) {
                $promotionStatus = ($overallAverage >= 75 && !$hasFailedSubject) ? 'Promoted' : 'Retained';
            }
        }

        $pdf = Pdf::loadView('pdf.report-card', compact(
            'student',
            'schoolYear',
            'gradeLevel',
            'section',
            'gradesData',
            'overallAverage',
            'promotionStatus'
        ));

        return $pdf->download('report_card_' . $student->lrn . '_' . $schoolYear->name . '.pdf');
    }
}
