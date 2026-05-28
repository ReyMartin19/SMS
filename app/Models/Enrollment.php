<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    protected $fillable = [
        'student_id', 'school_year_id', 'grade_level_id', 'section_id',
        'status', 'enrolled_at', 'track',
    ];
    
    protected $casts = [
        'enrolled_at' => 'date',
    ];
    
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    
    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }
    
    public function gradeLevel(): BelongsTo
    {
        return $this->belongsTo(GradeLevel::class);
    }
    
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }
}
