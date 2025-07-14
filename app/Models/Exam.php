<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'course_id'];
    public function quizzes() { return $this->belongsToMany(Quiz::class)->withPivot('order'); }
}
