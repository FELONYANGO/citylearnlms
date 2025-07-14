<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Enrollment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'course_id',
        'program_id',
        'status',
        'progress_percentage',
        'last_accessed_at',
        'completed_at',
        'paid_amount',
        'payment_status',
        'payment_date',
        'payment_method',
        'transaction_id',
        'access_expires_at',
        'enrollment_source',
        'referral_code',
        'coupon_code',
        'discount_amount',
        'certificate_issued',
        'certificate_issued_at',
        'certificate_number',
        'meta_data',
        'notes',
        'enrolled_at'
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'completed_at' => 'datetime',
        'payment_date' => 'datetime',
        'access_expires_at' => 'datetime',
        'certificate_issued_at' => 'datetime',
        'meta_data' => 'array',
        'progress_percentage' => 'float',
        'paid_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'certificate_issued' => 'boolean',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_EXPIRED = 'expired';
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_CANCELLED = 'cancelled';

    // Payment status constants
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_COMPLETED = 'completed';
    const PAYMENT_REFUNDED = 'refunded';
    const PAYMENT_FAILED = 'failed';

    // Relationships
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

    // Note: Payments are now handled through Orders, not direct enrollment payments
    // public function payments()
    // {
    //     return $this->hasMany(Payment::class);
    // }

    // Helper Methods
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function hasValidAccess(): bool
    {
        if ($this->access_expires_at === null) {
            return true;
        }
        return Carbon::now()->lt($this->access_expires_at);
    }

    public function markAsCompleted(): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'completed_at' => Carbon::now(),
            'progress_percentage' => 100
        ]);
    }

    public function updateProgress(float $percentage): void
    {
        $this->update([
            'progress_percentage' => $percentage,
            'last_accessed_at' => Carbon::now()
        ]);
    }

    public function recordPayment(float $amount, string $method, string $transactionId): void
    {
        $this->update([
            'paid_amount' => $amount,
            'payment_status' => self::PAYMENT_COMPLETED,
            'payment_method' => $method,
            'transaction_id' => $transactionId,
            'payment_date' => Carbon::now()
        ]);
    }

    public function issueCertificate(string $certificateNumber): void
    {
        $this->update([
            'certificate_issued' => true,
            'certificate_issued_at' => Carbon::now(),
            'certificate_number' => $certificateNumber
        ]);
    }

    /**
     * Check if user is already enrolled in a course
     */
    public static function isUserEnrolledInCourse(int $userId, int $courseId): bool
    {
        return self::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->whereNotIn('status', [self::STATUS_CANCELLED, self::STATUS_EXPIRED])
            ->exists();
    }

    /**
     * Check if user is already enrolled in a training program
     */
    public static function isUserEnrolledInProgram(int $userId, int $programId): bool
    {
        return self::where('user_id', $userId)
            ->where('training_program_id', $programId)
            ->whereNotIn('status', [self::STATUS_CANCELLED, self::STATUS_EXPIRED])
            ->exists();
    }

    /**
     * Get user's active enrollment for a course
     */
    public static function getUserCourseEnrollment(int $userId, int $courseId): ?self
    {
        return self::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->whereNotIn('status', [self::STATUS_CANCELLED, self::STATUS_EXPIRED])
            ->first();
    }

    /**
     * Get user's active enrollment for a training program
     */
    public static function getUserProgramEnrollment(int $userId, int $programId): ?self
    {
        return self::where('user_id', $userId)
            ->where('training_program_id', $programId)
            ->whereNotIn('status', [self::STATUS_CANCELLED, self::STATUS_EXPIRED])
            ->first();
    }

    /**
     * Create a safe enrollment (handles duplicate constraint violations)
     */
    public static function createSafeEnrollment(array $data): self|bool
    {
        try {
            return self::create($data);
        } catch (\Illuminate\Database\QueryException $e) {
            // If it's a duplicate entry error, return false
            if ($e->errorInfo[1] == 1062) {
                return false;
            }
            // Re-throw other database errors
            throw $e;
        }
    }

    /**
     * Check if enrollment has valid payment
     */
    public function hasValidPayment(): bool
    {
        return $this->payment_status === self::PAYMENT_COMPLETED;
    }

    /**
     * Check if enrollment is accessible (active and paid)
     */
    public function isAccessible(): bool
    {
        return $this->isActive() && $this->hasValidPayment() && $this->hasValidAccess();
    }
}
