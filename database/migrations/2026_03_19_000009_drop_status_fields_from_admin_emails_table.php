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
        if (Schema::hasColumn('admin_emails', 'sent_by')) {
            try {
                Schema::table('admin_emails', function (Blueprint $table) {
                    $table->dropForeign(['sent_by']);
                });
            } catch (\Throwable) {
                // Ignore when the foreign key is already missing or has a custom name.
            }
        }

        if (Schema::hasColumn('admin_emails', 'status')) {
            try {
                Schema::table('admin_emails', function (Blueprint $table) {
                    $table->dropIndex(['status']);
                });
            } catch (\Throwable) {
                // Ignore when the index is missing.
            }
        }

        if (Schema::hasColumn('admin_emails', 'sent_at')) {
            try {
                Schema::table('admin_emails', function (Blueprint $table) {
                    $table->dropIndex(['sent_at']);
                });
            } catch (\Throwable) {
                // Ignore when the index is missing.
            }
        }

        $columns = array_values(array_filter([
            'status',
            'sent_at',
            'last_error',
            'sent_by',
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
            if (! Schema::hasColumn('admin_emails', 'status')) {
                $table->string('status')->default('draft')->after('body');
            }

            if (! Schema::hasColumn('admin_emails', 'sent_at')) {
                $table->timestamp('sent_at')->nullable()->after('status');
            }

            if (! Schema::hasColumn('admin_emails', 'last_error')) {
                $table->text('last_error')->nullable()->after('sent_at');
            }

            if (! Schema::hasColumn('admin_emails', 'sent_by')) {
                $table->foreignId('sent_by')->nullable()->constrained('users')->nullOnDelete()->after('created_by');
            }

            $table->index('status');
            $table->index('sent_at');
        });
    }
};
