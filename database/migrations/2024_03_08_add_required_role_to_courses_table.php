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
        Schema::table('courses', function (Blueprint $table) {
            $table->string('required_role')->nullable()->after('status');
            $table->dateTime('published_at')->nullable()->after('status');
            $table->date('start_date')->nullable()->after('published_at');
            $table->date('end_date')->nullable()->after('start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('required_role');
            $table->dropColumn('published_at');
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
        });
    }
};
