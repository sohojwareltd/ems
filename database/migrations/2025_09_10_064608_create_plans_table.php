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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('price'); // Price in cents
            $table->string('currency', 3)->default('usd');
            $table->string('interval'); // month, year, week, day
            $table->integer('interval_count')->default(1);
            $table->integer('trial_period_days')->nullable();
            $table->boolean('active')->default(true);
            $table->json('features')->nullable(); // JSON array of features
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
