<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('media_resources', function (Blueprint $table) {
            $table->id();

            $table->foreignId('curriculum_item_id')
                ->constrained('curriculum_items')
                ->cascadeOnDelete();

            $table->string('title');
            $table->enum('resource_type', [
                'pdf', 'video', 'youtube', 'image', 'slide', 'text', 'link'
            ])->default('pdf');

            $table->string('file_url')->nullable();
            $table->string('video_url')->nullable();
            $table->longText('text_content')->nullable();
            $table->string('link_url')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('media_resources');
    }
};
