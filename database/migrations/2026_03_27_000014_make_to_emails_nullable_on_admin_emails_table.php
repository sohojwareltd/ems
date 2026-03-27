<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admin_emails', function (Blueprint $table): void {
            $table->text('to_emails')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('admin_emails', function (Blueprint $table): void {
            // Restore blank values before making NOT NULL again
            \Illuminate\Support\Facades\DB::table('admin_emails')
                ->whereNull('to_emails')
                ->update(['to_emails' => '']);

            $table->text('to_emails')->nullable(false)->change();
        });
    }
};
