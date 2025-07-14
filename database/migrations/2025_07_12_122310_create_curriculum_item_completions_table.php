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
        Schema::create('curriculum_item_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('curriculum_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->timestamp('completed_at');
            $table->integer('time_spent')->nullable()->comment('Time spent in seconds');
            $table->json('completion_data')->nullable()->comment('Additional completion data');
            $table->timestamps();

            // Ensure one completion per user per curriculum item
            $table->unique(['user_id', 'curriculum_item_id'], 'unique_user_curriculum_completion');

            // Indexes for better performance
            $table->index(['user_id', 'course_id']);
            $table->index(['curriculum_item_id', 'completed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curriculum_item_completions');
    }
};
