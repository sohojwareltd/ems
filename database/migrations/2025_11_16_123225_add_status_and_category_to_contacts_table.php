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
        Schema::table('contacts', function (Blueprint $table) {
            $table->enum('status', ['new', 'awaiting_response', 'completed'])->default('new')->after('message');
            $table->foreignId('contact_category_id')->nullable()->after('status')->constrained('contact_categories')->nullOnDelete();
            $table->text('admin_reply')->nullable()->after('contact_category_id');
            $table->timestamp('replied_at')->nullable()->after('admin_reply');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropForeign(['contact_category_id']);
            $table->dropColumn(['status', 'contact_category_id', 'admin_reply', 'replied_at']);
        });
    }
};
