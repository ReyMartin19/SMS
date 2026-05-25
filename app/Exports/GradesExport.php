<?php

namespace App\Exports;

use App\Models\StudentGrade;
use Illuminate\Database\Eloquent\Builder;

// When maatwebsite/excel is installed, you can uncomment these imports and the implements clause:
// use Maatwebsite\Excel\Concerns\FromQuery;
// use Maatwebsite\Excel\Concerns\WithHeadings;
// use Maatwebsite\Excel\Concerns\WithMapping;

class GradesExport // implements FromQuery, WithHeadings, WithMapping
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
        $query = StudentGrade::query()
            ->with(['student', 'subject', 'section', 'schoolYear']);

        if (!empty($this->filters['school_year_id'])) {
            $query->where('school_year_id', $this->filters['school_year_id']);
        }
        if (!empty($this->filters['section_id'])) {
            $query->where('section_id', $this->filters['section_id']);
        }
        if (!empty($this->filters['subject_id'])) {
            $query->where('subject_id', $this->filters['subject_id']);
        }
        if (!empty($this->filters['quarter'])) {
            $query->where('quarter', $this->filters['quarter']);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            '#',
            'Student Name',
            'LRN',
            'Subject',
            'Quarter',
            'Written Works',
            'Performance Tasks',
            'Quarterly Assessment',
            'Quarter Grade',
            'Remarks'
        ];
    }

    /**
     * @param StudentGrade $row
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
            $row->subject->name ?? '',
            $row->quarter,
            $row->written_works_score !== null ? number_format($row->written_works_score, 2) : '',
            $row->performance_task_score !== null ? number_format($row->performance_task_score, 2) : '',
            $row->quarterly_assessment_score !== null ? number_format($row->quarterly_assessment_score, 2) : '',
            $row->quarter_grade !== null ? number_format($row->quarter_grade, 2) : '',
            $row->remarks ?? ''
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
