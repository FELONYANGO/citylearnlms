<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('training_programs', function (Blueprint $table) {
            $table->foreignId('category_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('trainer_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('organization_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('training_programs', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['trainer_id']);
            $table->dropForeign(['organization_id']);

            $table->dropColumn([
                'category_id',
                'trainer_id',
                'organization_id'
            ]);
        });
    }
};
