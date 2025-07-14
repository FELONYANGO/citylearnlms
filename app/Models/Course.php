<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'slug',
        'description',
        'duration',
        'price',
        'thumbnail',
        'is_featured',
        'category_id',
        'type',
        'level',
        'published_at',
        'created_by',
        'status',
        'prerequisites',
        'objectives',
        'training_program_id',
        'required_role'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
        'prerequisites' => 'array',
        'objectives' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'published_at' => 'datetime'
    ];

    protected $appends = [
        'formatted_price',
        'duration_text',
        'slug',
        'enrollment_count',
        'has_started',
        'has_ended'
    ];

    /**
     * Get whether the course has started
     */
    public function getHasStartedAttribute(): bool
    {
        return $this->start_date ? $this->start_date->isPast() : true;
    }

    /**
     * Get whether the course has ended
     */
    public function getHasEndedAttribute(): bool
    {
        return $this->end_date ? $this->end_date->isPast() : false;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the value of the model's route key.
     */
    public function getRouteKey()
    {
        return $this->slug;
    }

    /**
     * Generate slug from title or return stored slug
     */
    public function getSlugAttribute(): string
    {
        // Check if we have a stored slug first (for explicit form input)
        if (!empty($this->attributes['slug'])) {
            return $this->attributes['slug'];
        }

        // Fallback to generated slug (maintains backward compatibility)
        return Str::slug($this->title ?? '');
    }

    // Mutator to handle training_program_id
    public function setTrainingProgramIdAttribute($value)
    {
        $this->attributes['training_program_id'] = ($value === 'none' || $value === '0') ? null : $value;
    }

    /**
     * Get formatted price with currency
     */
    public function getFormattedPriceAttribute(): string
    {
        return $this->price ? 'KSh ' . number_format($this->price, 2) : 'Free';
    }

    /**
     * Get formatted duration text
     */
    public function getDurationTextAttribute(): string
    {
        if ($this->duration === null) {
            return 'Duration not set';
        }

        return $this->duration;
    }

    /**
     * Get enrollment count
     */
    public function getEnrollmentCountAttribute(): int
    {
        return $this->enrollments()->where('status', 'active')->count();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function trainingProgram(): BelongsTo
    {
        return $this->belongsTo(TrainingProgram::class);
    }

    public function trainingPrograms(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(TrainingProgram::class, 'program_course')
            ->withPivot('order')
            ->withTimestamps()
            ->orderByPivot('order');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function curriculumItems(): HasMany
    {
        return $this->hasMany(CurriculumItem::class);
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get all order items for this course
     */
    public function orderItems(): MorphMany
    {
        return $this->morphMany(OrderItem::class, 'orderable');
    }

    /**
     * Get the purchasable amount for orders
     */
    public function getPurchaseAmount(): float
    {
        return $this->price ?? 0;
    }

    /**
     * Get the proper thumbnail URL for display
     */
    public function getThumbnailUrl()
    {
        if (!$this->thumbnail) {
            return null;
        }

        // If it's already a full URL, return as is
        if (filter_var($this->thumbnail, FILTER_VALIDATE_URL)) {
            return $this->thumbnail;
        }

        // Otherwise, generate storage URL
        return Storage::url($this->thumbnail);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function payments(): HasManyThrough
    {
        return $this->hasManyThrough(Payment::class, Enrollment::class);
    }

    protected static function booted()
    {
        static::creating(function (Course $course) {
            // Only auto-generate slug if not provided
            if (empty($course->slug)) {
                $slug = Str::slug($course->title);
                $originalSlug = $slug;
                $counter = 1;

                while (Course::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $counter++;
                }

                $course->slug = $slug;
            }
        });

        static::updating(function (Course $course) {
            // Regenerate slug if title changed and slug is empty
            if ($course->isDirty('title') && empty($course->slug)) {
                $slug = Str::slug($course->title);
                $originalSlug = $slug;
                $counter = 1;

                while (Course::where('slug', $slug)->where('id', '!=', $course->id)->exists()) {
                    $slug = $originalSlug . '-' . $counter++;
                }

                $course->slug = $slug;
            }
        });
    }
}
