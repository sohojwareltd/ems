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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('stripe_payment_intent_id')->nullable(); // For tracking Stripe payment intents
            $table->string('stripe_charge_id')->nullable(); // For tracking successful charges
            $table->unsignedInteger('amount');
            $table->string('currency', 3)->default('usd');
            $table->string('status'); // requires_payment_method, requires_confirmation, requires_action, processing, requires_capture, canceled, succeeded
            $table->string('type')->default('subscription'); // subscription, one_time
            $table->dateTime('paid_at')->nullable();
            $table->dateTime('failed_at')->nullable();
            $table->text('failure_reason')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
