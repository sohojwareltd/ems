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
            'name',
            'cc_emails',
            'bcc_emails',
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
            if (! Schema::hasColumn('admin_emails', 'name')) {
                $table->string('name')->nullable()->after('id');
            }

            if (! Schema::hasColumn('admin_emails', 'cc_emails')) {
                $table->text('cc_emails')->nullable()->after('to_emails');
            }

            if (! Schema::hasColumn('admin_emails', 'bcc_emails')) {
                $table->text('bcc_emails')->nullable()->after('cc_emails');
            }
        });
    }
};
