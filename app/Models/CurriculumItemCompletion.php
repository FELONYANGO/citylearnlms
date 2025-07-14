<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CurriculumItemCompletion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'curriculum_item_id',
        'course_id',
        'completed_at',
        'time_spent',
        'completion_data',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'completion_data' => 'array',
    ];

    /**
     * Get the user who completed the curriculum item.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the curriculum item that was completed.
     */
    public function curriculumItem(): BelongsTo
    {
        return $this->belongsTo(CurriculumItem::class);
    }

    /**
     * Get the course this completion belongs to.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Check if a user has completed a specific curriculum item.
     */
    public static function isCompleted(int $userId, int $curriculumItemId): bool
    {
        return static::where('user_id', $userId)
            ->where('curriculum_item_id', $curriculumItemId)
            ->exists();
    }

    /**
     * Get completion percentage for a user in a course.
     */
    public static function getCompletionPercentage(int $userId, int $courseId): float
    {
        $totalItems = CurriculumItem::where('course_id', $courseId)->count();
        if ($totalItems === 0) return 100.0;

        $completedItems = static::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->count();

        return round(($completedItems / $totalItems) * 100, 1);
    }
}
