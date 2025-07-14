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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('instructions')->nullable();

            $table->foreignId('curriculum_item_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->integer('time_limit')->nullable()->comment('Time limit in minutes');
            $table->integer('passing_score')->nullable()->comment('Passing score percentage');

            $table->boolean('is_practice')->default(false);
            $table->boolean('is_final')->default(false);
            $table->boolean('randomize_questions')->default(false);

            $table->integer('max_attempts')->nullable();
            $table->enum('show_feedback', ['immediate', 'after_completion', 'none'])
                ->default('after_completion');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
