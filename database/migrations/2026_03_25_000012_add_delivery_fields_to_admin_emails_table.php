<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admin_emails', function (Blueprint $table) {
            if (! Schema::hasColumn('admin_emails', 'cc_emails')) {
                $table->text('cc_emails')->nullable()->after('to_emails');
            }

            if (! Schema::hasColumn('admin_emails', 'bcc_emails')) {
                $table->text('bcc_emails')->nullable()->after('cc_emails');
            }

            if (! Schema::hasColumn('admin_emails', 'attachments')) {
                $table->json('attachments')->nullable()->after('email_groups');
            }

            if (! Schema::hasColumn('admin_emails', 'attachment_file_names')) {
                $table->json('attachment_file_names')->nullable()->after('attachments');
            }
        });
    }

    public function down(): void
    {
        $columns = array_values(array_filter([
            'cc_emails',
            'bcc_emails',
            'attachments',
            'attachment_file_names',
        ], fn (string $column): bool => Schema::hasColumn('admin_emails', $column)));

        if ($columns === []) {
            return;
        }

        Schema::table('admin_emails', function (Blueprint $table) use ($columns) {
            $table->dropColumn($columns);
        });
    }
};