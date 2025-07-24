<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IssuedCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'certificate_id',
        'issued_at',
        'file_path',
        'metadata',
        'verification_code',
    ];

    protected $casts = [
        'metadata' => 'array',
        'issued_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function certificate(): BelongsTo
    {
        return $this->belongsTo(Certificate::class);
    }
}
