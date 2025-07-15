<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quiz extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'instructions',
        'curriculum_item_id',
        'time_limit',
        'passing_score',
        'is_practice',
        'is_final',
        'randomize_questions',
        'max_attempts',
        'show_feedback',
        'status',
    ];

    public function curriculumItem()
    {
        return $this->belongsTo(CurriculumItem::class);
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function attempts()
    {
        return $this->hasMany(UserQuizAttempt::class);
    }

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exam_quizzes')
            ->withPivot('order', 'weight')
            ->orderByPivot('order');
    }
}
