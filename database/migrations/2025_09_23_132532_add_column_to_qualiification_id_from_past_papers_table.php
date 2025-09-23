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
        Schema::table('past_papers', function (Blueprint $table) {
            $table->unsignedBigInteger('qualiification_id')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->unsignedBigInteger('examboard_id')->nullable();
            $table->unsignedBigInteger('resource_type_id')->nullable();

            $table->json('tags')->nullable();
            $table->json('options')->nullable(); 
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('past_papers', function (Blueprint $table) {
            $table->dropColumn([
                'qualiification_id',
                'subject_id',
                'examboard_id',
                'resource_type_id',
                'tags',
                'options',
                'meta_title',
                'meta_description',
                'meta_keywords'
            ]);
        });
    }
};
