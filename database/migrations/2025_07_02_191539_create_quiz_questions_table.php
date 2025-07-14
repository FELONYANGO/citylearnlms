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
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('quiz_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->text('question_text');
            $table->enum('question_type', [
                'multiple_choice',
                'true_false',
                'short_answer'
            ])->default('multiple_choice');
            $table->integer('points')->default(1);
            $table->integer('order')->default(0);

            $table->text('explanation')->nullable()->comment('Explanation shown after answering');
            $table->boolean('is_required')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};
