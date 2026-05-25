<?php

namespace App\Exports;

use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Builder;

// When maatwebsite/excel is installed, you can uncomment these imports and the implements clause:
// use Maatwebsite\Excel\Concerns\FromQuery;
// use Maatwebsite\Excel\Concerns\WithHeadings;
// use Maatwebsite\Excel\Concerns\WithMapping;

class EnrollmentExport // implements FromQuery, WithHeadings, WithMapping
{
    protected array $filters;
    protected int $index = 0;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * @return Builder
     */
    public function query()
    {
        $query = Enrollment::query()
            ->with(['student', 'gradeLevel', 'section', 'schoolYear']);

        if (!empty($this->filters['school_year_id'])) {
            $query->where('school_year_id', $this->filters['school_year_id']);
        }
        if (!empty($this->filters['grade_level_id'])) {
            $query->where('grade_level_id', $this->filters['grade_level_id']);
        }
        if (!empty($this->filters['section_id'])) {
            $query->where('section_id', $this->filters['section_id']);
        }
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            '#',
            'Student Name',
            'LRN',
            'Grade Level',
            'Section',
            'School Year',
            'Status',
            'Enrolled At'
        ];
    }

    /**
     * @param Enrollment $row
     */
    public function map($row): array
    {
        $this->index++;
        
        $fullName = '';
        if ($row->student) {
            $fullName = trim(sprintf(
                '%s, %s %s %s',
                $row->student->last_name,
                $row->student->first_name,
                $row->student->middle_name,
                $row->student->suffix
            ));
        }

        return [
            $this->index,
            $fullName,
            $row->student->lrn ?? '',
            $row->gradeLevel->name ?? '',
            $row->section->name ?? '',
            $row->schoolYear->name ?? '',
            ucfirst($row->status),
            $row->enrolled_at ? $row->enrolled_at->format('Y-m-d') : ''
        ];
    }

    /**
     * Fallback method to output directly as CSV
     */
    public function toCsv($stream): void
    {
        fputcsv($stream, $this->headings());
        
        $this->query()->chunk(500, function ($rows) use ($stream) {
            foreach ($rows as $row) {
                fputcsv($stream, $this->map($row));
            }
        });
    }
}
