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
        Schema::table('email_reply_messages', function (Blueprint $table): void {
            $table->string('direction', 20)
                ->default('inbound')
                ->after('email_recipient_id')
                ->comment('inbound or outbound');

            $table->index('direction');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_reply_messages', function (Blueprint $table): void {
            $table->dropIndex(['direction']);
            $table->dropColumn('direction');
        });
    }
};
