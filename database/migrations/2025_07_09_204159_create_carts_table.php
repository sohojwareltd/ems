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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('cart_id')->unique()->index(); // UUID for cart
            $table->string('status')->default('active')->index(); // Cart status
            $table->timestamp('expires_at')->nullable()->index(); // Expiry for abandoned carts
            $table->string('session_id')->nullable()->index();
            $table->string('guest_id')->nullable()->index();
            $table->decimal('subtotal', 10, 2)->default(0.00);
            $table->decimal('tax', 10, 2)->default(0.00);
            $table->decimal('shipping', 10, 2)->default(0.00);
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->decimal('total', 10, 2)->default(0.00);
            $table->string('coupon_code')->nullable();
            $table->json('coupon_data')->nullable();
            $table->string('shipping_method')->nullable();
            $table->json('shipping_address')->nullable();
            $table->json('billing_address')->nullable();
            $table->string('currency', 3)->default('USD');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_activity')->nullable(); 
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id', 'is_active']);
            $table->index(['session_id', 'is_active']);
            $table->index(['guest_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
