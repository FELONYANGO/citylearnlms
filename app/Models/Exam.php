<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'course_id',
        'description',
        'time_limit',
        'passing_score',
        'max_attempts',
        'status',
        'is_practice',
        'show_feedback',
        'randomize_questions',
    ];

    protected $casts = [
        'time_limit' => 'integer',
        'passing_score' => 'integer',
        'max_attempts' => 'integer',
        'is_practice' => 'boolean',
        'show_feedback' => 'boolean',
        'randomize_questions' => 'boolean',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function quizzes(): BelongsToMany
    {
        return $this->belongsToMany(Quiz::class, 'exam_quizzes')
            ->withPivot('order', 'weight')
            ->orderByPivot('order');
    }

    public function attempts()
    {
        return $this->hasMany(UserQuizAttempt::class);
    }

    public function getProgressForUser($userId)
    {
        $totalQuizzes = $this->quizzes()->count();
        if ($totalQuizzes === 0) return 0;

        $completedQuizzes = $this->quizzes()
            ->whereHas('attempts', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->where('score', '>=', $this->passing_score);
            })
            ->count();

        return ($completedQuizzes / $totalQuizzes) * 100;
    }

    public function isCompletedByUser($userId): bool
    {
        return $this->getProgressForUser($userId) >= 100;
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'gray',
            'published' => 'green',
            'archived' => 'red',
            default => 'gray',
        };
    }
}
