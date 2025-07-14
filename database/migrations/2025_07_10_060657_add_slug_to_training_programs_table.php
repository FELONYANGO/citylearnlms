<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\TrainingProgram;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('training_programs', function (Blueprint $table) {
            $table->string('slug')->after('title')->unique();
        });

        // Generate slugs for existing programs
        TrainingProgram::all()->each(function ($program) {
            $program->slug = Str::slug($program->title);
            $program->save();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('training_programs', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
