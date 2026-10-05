<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    public const TYPE_HOMEWORK = 'homework';
    public const TYPE_CLASS_ACTIVITY = 'class_activity';
    public const TYPE_PRESENTATION = 'presentation';

    public const STATUS_ACTIVE = 'active';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'title',
        'description',
        'type',
        'assigned_at',
        'due_at',
        'status',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'assigned_at' => 'datetime',
            'due_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isExpired(): bool
    {
        return now()->greaterThan($this->due_at);
    }

    public function canSubmit(): bool
    {
        return $this->isActive()
            && now()->lessThanOrEqualTo($this->due_at);
    }

    public function canBeGraded(): bool
    {
        return $this->isActive()
            && now()->greaterThan($this->due_at);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            self::TYPE_HOMEWORK => 'Tarea en casa',
            self::TYPE_CLASS_ACTIVITY => 'Actividad en clase',
            self::TYPE_PRESENTATION => 'Exposición',
            default => 'Sin tipo',
        };
    }
}