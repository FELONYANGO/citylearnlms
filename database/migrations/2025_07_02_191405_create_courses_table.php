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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('price', 10, 2)->default(0);
            $table->string('thumbnail')->nullable();
            $table->enum('type', ['file', 'video', 'blended', 'live'])->default('file');
            $table->unsignedTinyInteger('level')->default(1);
            $table->timestamp('published_at')->nullable();
            $table->string('status')->default('draft');
            $table->boolean('is_featured')->default(false);

            $table->json('prerequisites')->nullable();
            $table->json('objectives')->nullable();

            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('training_program_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
