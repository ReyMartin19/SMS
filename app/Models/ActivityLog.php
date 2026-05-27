<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'module',
        'description',
        'subject_type',
        'subject_id',
        'old_values',
        'new_values',
        'ip_address',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function log(
        string $action,
        string $module,
        string $description,
        array $options = []
    ): void {
        static::create([
            'user_id'      => auth()->id(),
            'action'       => $action,
            'module'       => $module,
            'description'  => $description,
            'subject_type' => $options['subject_type'] ?? null,
            'subject_id'   => $options['subject_id'] ?? null,
            'old_values'   => $options['old_values'] ?? null,
            'new_values'   => $options['new_values'] ?? null,
            'ip_address'   => request()->ip(),
        ]);
    }
}
