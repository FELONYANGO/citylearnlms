<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Split name into first and last name (keeping original name for backward compatibility)
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');

            // Contact Information
            $table->string('phone')->nullable()->after('email');
            $table->string('country')->nullable()->after('phone');
            $table->string('city')->nullable()->after('country');

            // Professional Information
            $table->enum('professional_status', [
                'professional',
                'non_professional',
                'student',
                'unemployed',
                'self_employed'
            ])->nullable()->after('city');

            $table->string('industry')->nullable()->after('professional_status');
            $table->string('job_title')->nullable()->after('industry');
            $table->enum('years_experience', [
                '0-1',
                '2-5',
                '6-10',
                '11-15',
                '16+'
            ])->nullable()->after('job_title');

            // Organization Information
            $table->string('organization_name')->nullable()->after('organization_id');
            $table->enum('organization_type', [
                'company',
                'ngo',
                'government',
                'educational_institution',
                'self_employed',
                'other'
            ])->nullable()->after('organization_name');

            $table->enum('company_size', [
                '1-10',
                '11-50',
                '51-200',
                '201-1000',
                '1000+'
            ])->nullable()->after('organization_type');

            // Learning Preferences
            $table->json('learning_goals')->nullable()->after('company_size');
            $table->enum('preferred_learning_format', [
                'self_paced',
                'instructor_led',
                'blended'
            ])->default('self_paced')->after('learning_goals');

            $table->json('time_availability')->nullable()->after('preferred_learning_format');

            // Demographics (Optional)
            $table->enum('age_range', [
                '18-25',
                '26-35',
                '36-45',
                '46-55',
                '56+'
            ])->nullable()->after('time_availability');

            $table->enum('education_level', [
                'high_school',
                'diploma',
                'bachelor',
                'master',
                'phd',
                'other'
            ])->nullable()->after('age_range');

            // System Information
            $table->string('referral_source')->nullable()->after('education_level');
            $table->string('preferred_language')->default('en')->after('referral_source');
            $table->text('special_accommodations')->nullable()->after('preferred_language');

            // Profile completion tracking
            $table->boolean('profile_completed')->default(false)->after('special_accommodations');
            $table->timestamp('profile_completed_at')->nullable()->after('profile_completed');

            // Additional metadata
            $table->json('profile_metadata')->nullable()->after('profile_completed_at');

            // Indexes for better performance
            $table->index('professional_status');
            $table->index('industry');
            $table->index(['country', 'city']);
            $table->index('referral_source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex(['professional_status']);
            $table->dropIndex(['industry']);
            $table->dropIndex(['country', 'city']);
            $table->dropIndex(['referral_source']);

            // Drop all added columns
            $table->dropColumn([
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
            ]);
        });
    }
};
