<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PassCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'training_program_id',
        'template_id',
        'issue_date',
        'expiration_date',
        'license_number',
        'qr_code_data',
        'qr_code_path',
        'metadata',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_active' => 'boolean',
        'issue_date' => 'datetime',
        'expiration_date' => 'datetime',
    ];

    public function trainingProgram(): BelongsTo
    {
        return $this->belongsTo(TrainingProgram::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(PassCardTemplate::class, 'template_id');
    }
}
