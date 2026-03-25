<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admin_emails', function (Blueprint $table) {
            $table->json('email_groups')->nullable()->after('to_emails');
        });
    }

    public function down(): void
    {
        Schema::table('admin_emails', function (Blueprint $table) {
            $table->dropColumn('email_groups');
        });
    }
};
