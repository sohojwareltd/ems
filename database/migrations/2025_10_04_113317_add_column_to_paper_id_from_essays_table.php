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
            $table->unsignedBigInteger('paper_id')->nullable()->after('topic_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('essays', function (Blueprint $table) {
            $table->dropColumn('paper_id');
        });
    }
};
