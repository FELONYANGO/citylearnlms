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
        Schema::create('training_programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->integer('level')->comment('1–4');
            $table->text('audience');
            $table->text('objectives');
            $table->json('assessment_method');
            $table->json('prerequisites')->nullable();
            $table->integer('duration_days')->nullable();
            $table->string('certification')->nullable();
            $table->decimal('fee', 10, 2)->nullable();
            $table->decimal('exam_fee', 10, 2)->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->string('banner')->nullable();
            $table->boolean('is_public')->default(true);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_programs');
    }
};
