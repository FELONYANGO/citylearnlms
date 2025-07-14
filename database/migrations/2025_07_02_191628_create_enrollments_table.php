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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('program_id')->nullable()->constrained('training_programs')->onDelete('cascade');

            // Enrollment Status and Progress
            $table->enum('status', [
                'pending',    // Initial state
                'active',     // Currently enrolled
                'completed',  // Finished successfully
                'expired',    // Access expired
                'suspended',  // Temporarily suspended
                'cancelled'   // Cancelled enrollment
            ])->default('pending');
            $table->float('progress_percentage')->default(0);
            $table->timestamp('last_accessed_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Payment and Access Information
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->enum('payment_status', ['pending', 'completed', 'refunded', 'failed'])->default('pending');
            $table->timestamp('payment_date')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->timestamp('access_expires_at')->nullable();

            // Enrollment Source and Additional Info
            $table->string('enrollment_source')->nullable(); // e.g., 'direct', 'referral', 'promotion'
            $table->string('referral_code')->nullable();
            $table->string('coupon_code')->nullable();
            $table->decimal('discount_amount', 10, 2)->default(0);

            // Certificate Information
            $table->boolean('certificate_issued')->default(false);
            $table->timestamp('certificate_issued_at')->nullable();
            $table->string('certificate_number')->nullable()->unique();

            // Meta Information
            $table->json('meta_data')->nullable(); // For any additional data
            $table->text('notes')->nullable(); // Admin/system notes

            // Standard Timestamps
            $table->timestamp('enrolled_at');
            $table->timestamps();
            $table->softDeletes(); // Allow soft deletes for enrollment history

            // Indexes for better query performance
            $table->index(['status', 'enrolled_at']);
            $table->index(['payment_status', 'payment_date']);
            $table->index('access_expires_at');
            $table->index('certificate_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
