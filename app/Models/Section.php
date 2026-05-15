<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    protected $fillable = [
        'grade_level_id', 'name', 'room_number', 'capacity',
    ];
    
    public function gradeLevel(): BelongsTo
    {
        return $this->belongsTo(GradeLevel::class);
    }
    
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }
}
