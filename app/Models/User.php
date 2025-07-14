<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasRoles, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'organization_id',
        'first_login',
        // New profile fields
        'first_name',
        'last_name',
        'phone',
        'country',
        'city',
        'professional_status',
        'industry',
        'job_title',
        'years_experience',
        'organization_name',
        'organization_type',
        'company_size',
        'learning_goals',
        'preferred_learning_format',
        'time_availability',
        'age_range',
        'education_level',
        'referral_source',
        'preferred_language',
        'special_accommodations',
        'profile_completed',
        'profile_completed_at',
        'profile_metadata'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // When a user is created or updated
        static::saved(function ($user) {
            // Sync the enum role with Spatie roles
            if ($user->role) {
                // Remove all current roles
                $user->syncRoles([]);
                // Assign the new role
                $user->assignRole($user->role);
            }
        });
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
    public function quizAttempts()
    {
        return $this->hasMany(UserQuizAttempt::class);
    }
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
}
