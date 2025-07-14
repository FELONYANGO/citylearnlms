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
        Schema::create('homepage_settings', function (Blueprint $table) {
            $table->id();

            // Media Assets
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('hero_video')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('about_image')->nullable();
            $table->json('testimonial_avatars')->nullable();

            // Content Settings
            $table->string('site_title')->default('Nairobi County Training Center');
            $table->string('hero_title')->default('Empowering Communities Through Education');
            $table->text('hero_subtitle')->nullable();
            $table->string('hero_cta_text')->default('Explore Programs');
            $table->string('about_title')->default('About Our Training Center');
            $table->text('about_description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homepage_settings');
    }
};
