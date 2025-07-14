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
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('file_path')->nullable(); // For uploaded HTML template
            $table->text('html_content')->nullable(); // For editor content
            $table->json('configuration')->nullable(); // Template settings
            $table->json('placeholders')->nullable(); // Dynamic field config
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('template_id')->constrained('certificate_templates')->onDelete('restrict');
            $table->json('metadata')->nullable(); // Dynamic data used in generation
            $table->string('file_path')->nullable(); // Path to generated PDF
            $table->boolean('is_generated')->default(false);
            $table->timestamp('issued_at');
            $table->timestamps();
        });

        Schema::create('signature_authorities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position');
            $table->string('signature_path');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('certificate_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certificate_id')->constrained()->onDelete('cascade');
            $table->foreignId('signature_authority_id')->constrained()->onDelete('cascade');
            $table->string('signature_path')->nullable(); // Optional override
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_signatures');
        Schema::dropIfExists('signature_authorities');
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('certificate_templates');
    }
};
