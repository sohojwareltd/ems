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
        Schema::create('email_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_log_id')->constrained('email_logs')->onDelete('cascade');
            $table->string('email', 255);
            $table->enum('type', ['to', 'cc', 'bcc'])->default('to')->comment('Recipient type');
            $table->timestamp('replied_at')->nullable()->comment('When this recipient replied');
            $table->timestamps();

            // Indexes for common queries
            $table->index('email_log_id');
            $table->index('email');
            $table->index('replied_at');
            $table->index(['email_log_id', 'replied_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_recipients');
    }
};
