<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentGrade extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
        'section_id',
        'school_year_id',
        'quarter',
        'written_works_score',
        'performance_task_score',
        'quarterly_assessment_score',
        'quarter_grade',
        'remarks',
    ];

    protected $casts = [
        'written_works_score' => 'decimal:2',
        'performance_task_score' => 'decimal:2',
        'quarterly_assessment_score' => 'decimal:2',
        'quarter_grade' => 'decimal:2',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function computeQuarterGrade()
    {
        if ($this->written_works_score === null || $this->performance_task_score === null || $this->quarterly_assessment_score === null) {
            $this->quarter_grade = null;
            $this->remarks = 'Incomplete';
            return;
        }

        $this->quarter_grade = ($this->written_works_score * 0.25) + ($this->performance_task_score * 0.50) + ($this->quarterly_assessment_score * 0.25);

        if ($this->quarter_grade >= 75) {
            $this->remarks = 'Passed';
        } else {
            $this->remarks = 'Failed';
        }
    }

    public function getFinalGradeAttribute()
    {
        $grades = static::where('student_id', $this->student_id)
            ->where('subject_id', $this->subject_id)
            ->where('school_year_id', $this->school_year_id)
            ->get();

        if ($grades->count() < 4 || $grades->containsStrict('quarter_grade', null)) {
            return null; // Cannot compute final grade if not all quarters are graded
        }

        return $grades->avg('quarter_grade');
    }
}
