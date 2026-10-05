<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'student_id',
        'file_path',
        'original_filename',
        'file_size',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
        ];
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function grade(): HasOne
    {
        return $this->hasOne(Grade::class);
    }

    public function hasPublishedGrade(): bool
    {
        return $this->grade !== null
            && $this->grade->isPublished();
    }
}