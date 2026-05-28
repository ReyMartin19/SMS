<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'code',
        'type',
        'grade_level_id',
        'track',
    ];

    public function scopeForGradeLevel($query, GradeLevel $gradeLevel): void
    {
        $query->where('type', $gradeLevel->type)
              ->where(function ($q) use ($gradeLevel) {
                  $q->whereNull('grade_level_id')
                    ->orWhere('grade_level_id', $gradeLevel->id);
              });
    }

    public function scopeForTrack($query, string $track): void
    {
        $query->where('track', $track);
    }

    public function scopeCoreSubjects($query): void
    {
        $query->whereNull('track');
    }

    public function gradeLevel(): BelongsTo
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(TeacherAssignment::class);
    }

    public function teachers(): HasManyThrough
    {
        return $this->hasManyThrough(Teacher::class, TeacherAssignment::class, 'subject_id', 'id', 'id', 'teacher_id')->distinct();
    }
}
