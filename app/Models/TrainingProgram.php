<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\OrderItem;

class TrainingProgram extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'level',
        'slug',
        'audience',
        'objectives',
        'assessment_method',
        'prerequisites',
        'duration_days',
        'certification',
        'fee',
        'exam_fee',
        'created_by',
        'trainer_id',
        'category_id',
        'organization_id',
        'status',
        'banner',
        'is_public',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'assessment_method' => 'array',
        'prerequisites' => 'array',
        'objectives' => 'array',
        'is_public' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];


    protected $appends = ['period', 'total_fee'];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        // Use ID for admin/filament routes, slug for frontend
        if (request()->is('admin/*')) {
            return 'id';
        }
        return 'slug';
    }

    /**
     * Resolve the route binding.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        if (request()->is('admin/*')) {
            return $this->where('id', $value)->firstOrFail();
        }
        return $this->where('slug', $value)->firstOrFail();
    }


    /**
     * Get the value of the model's route key.
     */
    public function getRouteKey()
    {
        if (request()->is('admin/*')) {
            return $this->id;
        }
        return $this->slug;
    }

    /**
     * Generate slug from title
     */
    public function getSlugAttribute(): string
    {
        return Str::slug($this->title);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($program) {
            if (empty($program->slug)) {
                $program->slug = static::generateUniqueSlug($program->title);
            }
        });

        static::updating(function ($program) {
            if ($program->isDirty('title') && empty($program->slug)) {
                $program->slug = static::generateUniqueSlug($program->title);
            }
        });
    }

    /**
     * Generate a unique slug from the given title.
     */
    protected static function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }

    /**
     * Get the formatted duration period.
     * Calculates months and days from duration_days
     */
    public function getPeriodAttribute(): string
    {
        if (!$this->duration_days) {
            return '6 months'; // Default fallback
        }

        $months = floor($this->duration_days / 30);
        $remainingDays = $this->duration_days % 30;

        if ($months > 0 && $remainingDays > 0) {
            return sprintf(
                '%d %s %d %s',
                $months,
                $months === 1 ? 'month' : 'months',
                $remainingDays,
                $remainingDays === 1 ? 'day' : 'days'
            );
        } elseif ($months > 0) {
            return sprintf(
                '%d %s',
                $months,
                $months === 1 ? 'month' : 'months'
            );
        } else {
            return sprintf(
                '%d %s',
                $remainingDays,
                $remainingDays === 1 ? 'day' : 'days'
            );
        }
    }



    /**
     * Get the total fee including exam fee if present
     */
    public function getTotalFeeAttribute(): float
    {
        return ($this->fee ?? 0) + ($this->exam_fee ?? 0);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'program_course')
            ->withPivot('order')
            ->withTimestamps()
            ->orderByPivot('order');
    }

    /**
     * Get the certificates for the training program
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class, 'program_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get all order items for this program
     */
    public function orderItems(): MorphMany
    {
        return $this->morphMany(OrderItem::class, 'orderable');
    }

    /**
     * Get the formatted price attribute
     */
    public function getFormattedPriceAttribute(): string
    {
        return $this->price ? 'KSh ' . number_format($this->price, 2) : 'Free';
    }

    /**
     * Get the purchasable amount for orders
     */
    public function getPurchaseAmount(): float
    {
        return $this->total_fee ?? 0;
    }
}
