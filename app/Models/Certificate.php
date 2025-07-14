<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'certificate_number',
        'user_id',
        'course_id',
        'program_id',
        'template_id',
        'metadata',
        'file_path',
        'is_generated',
        'issued_at',
        'notes'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_generated' => 'boolean',
        'issued_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function trainingProgram(): BelongsTo
    {
        return $this->belongsTo(TrainingProgram::class, 'program_id');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(CertificateTemplate::class);
    }
}
