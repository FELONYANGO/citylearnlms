<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quiz_options', function (Blueprint $table) {
            $table->id();

            $table->foreignId('quiz_question_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('option_text');
            $table->boolean('is_correct')->default(false);
            $table->text('explanation')->nullable()->comment('Why this option is correct/incorrect');
            $table->integer('order')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_options');
    }
};
