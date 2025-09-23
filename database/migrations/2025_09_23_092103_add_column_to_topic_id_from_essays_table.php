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
        Schema::table('essays', function (Blueprint $table) {
            $table->foreignId('topic_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('year')->nullable()->default(0);
            $table->string('month')->nullable();
            $table->integer('marks')->nullable()->default(0);
            $table->string('ppt_file')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('essays', function (Blueprint $table) {
            $table->dropColumn(['topic_id', 'year', 'month', 'marks', 'ppt_file']);
        });
    }
};
