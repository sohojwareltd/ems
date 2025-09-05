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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('product_name');
            $table->string('sku');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->json('variant')->nullable(); // Store variant information (size, color, etc.)
            $table->string('variant_sku')->nullable();
            $table->json('options')->nullable(); // Store additional options
            $table->text('notes')->nullable(); // Customer notes for this item
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['cart_id', 'product_id']);
            $table->index('sku');
            $table->index('variant_sku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
