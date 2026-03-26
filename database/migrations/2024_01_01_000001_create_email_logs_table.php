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
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_email_id')->constrained('admin_emails')->onDelete('cascade');
            $table->string('subject', 255);
            $table->longText('body');
            $table->string('from_email', 255);
            $table->integer('total_recipients')->default(0)->comment('Total count of to, cc, bcc recipients');
            $table->integer('replied_count')->default(0)->comment('Count of recipients who replied');
            $table->integer('pending_count')->default(0)->comment('Count of recipients who have not replied');
            $table->enum('status', ['queued', 'sent', 'failed', 'partial'])->default('queued');
            $table->text('error_message')->nullable()->comment('Error details if sending failed');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            // Indexes for common queries
            $table->index('admin_email_id');
            $table->index('status');
            $table->index('sent_at');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
