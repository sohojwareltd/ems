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
        $columns = array_values(array_filter([
            'from_name',
            'from_email',
            'reply_to_name',
            'reply_to_email',
        ], fn (string $column): bool => Schema::hasColumn('admin_emails', $column)));

        if ($columns === []) {
            return;
        }

        Schema::table('admin_emails', function (Blueprint $table) use ($columns) {
            $table->dropColumn($columns);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_emails', function (Blueprint $table) {
            if (! Schema::hasColumn('admin_emails', 'from_name')) {
                $table->string('from_name')->nullable()->after('to_emails');
            }

            if (! Schema::hasColumn('admin_emails', 'from_email')) {
                $table->string('from_email')->nullable()->after('from_name');
            }

            if (! Schema::hasColumn('admin_emails', 'reply_to_name')) {
                $table->string('reply_to_name')->nullable()->after('from_email');
            }

            if (! Schema::hasColumn('admin_emails', 'reply_to_email')) {
                $table->string('reply_to_email')->nullable()->after('reply_to_name');
            }
        });
    }
};
