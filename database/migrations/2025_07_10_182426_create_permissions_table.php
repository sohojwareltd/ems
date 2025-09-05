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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'users.view', 'products.create'
            $table->string('display_name'); // e.g., 'View Users', 'Create Products'
            $table->text('description')->nullable(); // Optional description
            $table->string('group')->default('general'); // Group permissions (e.g., 'users', 'products', 'orders')
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
