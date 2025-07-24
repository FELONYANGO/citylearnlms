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
        Schema::create('pass_cards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('training_program_id')->constrained()->onDelete('cascade');
            $table->foreignId('template_id')->constrained('pass_card_templates')->onDelete('restrict');
            $table->timestamp('issue_date')->nullable();
            $table->timestamp('expiration_date')->nullable();
            $table->string('license_number')->nullable();
            $table->text('qr_code_data')->nullable();
            $table->string('qr_code_path')->nullable();
            $table->json('metadata')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pass_cards');
    }
};
