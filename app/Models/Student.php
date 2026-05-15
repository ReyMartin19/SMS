<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    protected $fillable = [
        'user_id', 'first_name', 'middle_name', 'last_name', 'suffix',
        'gender', 'birthdate', 'birthplace', 'address', 'contact_number',
        'guardian_name', 'guardian_relationship', 'guardian_contact',
        'lrn', 'photo', 'status',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }
    
    public function activeEnrollment(): HasOne
    {
        return $this->hasOne(Enrollment::class)->where('status', 'enrolled');
    }
}
