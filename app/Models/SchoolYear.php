<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolYear extends Model
{
    protected $fillable = [
        'name', 'start_date', 'end_date', 'is_active',
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];
    
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }
    
    // Ensure only one school year is active at a time
    public static function activate(self $schoolYear): void
    {
        self::query()->update(['is_active' => false]);
        $schoolYear->update(['is_active' => true]);
    }
}
