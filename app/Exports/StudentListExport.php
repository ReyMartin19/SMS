<?php

namespace App\Exports;

use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;

// When maatwebsite/excel is installed, you can uncomment these imports and the implements clause:
// use Maatwebsite\Excel\Concerns\FromQuery;
// use Maatwebsite\Excel\Concerns\WithHeadings;
// use Maatwebsite\Excel\Concerns\WithMapping;

class StudentListExport // implements FromQuery, WithHeadings, WithMapping
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
        $query = Student::query();

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }
        if (!empty($this->filters['gender'])) {
            $query->where('gender', $this->filters['gender']);
        }
        if (!empty($this->filters['grade_level_id'])) {
            $query->whereHas('enrollments', function (Builder $q) {
                $q->where('grade_level_id', $this->filters['grade_level_id'])
                  ->where('status', 'enrolled');
            });
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            '#',
            'Last Name',
            'First Name',
            'Middle Name',
            'LRN',
            'Gender',
            'Birthdate',
            'Address',
            'Status'
        ];
    }

    /**
     * @param Student $row
     */
    public function map($row): array
    {
        $this->index++;

        return [
            $this->index,
            $row->last_name,
            $row->first_name,
            $row->middle_name ?? '',
            $row->lrn ?? '',
            ucfirst($row->gender),
            $row->birthdate ? $row->birthdate->format('Y-m-d') : '',
            $row->address ?? '',
            ucfirst($row->status)
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
