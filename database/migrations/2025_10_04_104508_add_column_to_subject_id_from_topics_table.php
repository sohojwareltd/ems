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
        Schema::table('topics', function (Blueprint $table) {
            $table->unsignedBigInteger('subject_id')->nullable()->after('id');
            $table->unsignedBigInteger('paper_id')->nullable()->after('subject_id');
        });
    }

    public function down(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->dropColumn('subject_id');
            $table->dropColumn('paper_id');
        });
    }
};
