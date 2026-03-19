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
            'coupon_starts_at',
            'coupon_expires_at',
            'coupon_access_months',
            'coupon_access_starts_at',
            'coupon_access_ends_at',
        ], fn (string $column): bool => Schema::hasColumn('plans', $column)));

        if ($columns === []) {
            return;
        }

        Schema::table('plans', function (Blueprint $table) use ($columns) {
            $table->dropColumn($columns);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            if (! Schema::hasColumn('plans', 'coupon_starts_at')) {
                $table->dateTime('coupon_starts_at')->nullable()->after('coupon_total_used');
            }

            if (! Schema::hasColumn('plans', 'coupon_expires_at')) {
                $table->dateTime('coupon_expires_at')->nullable()->after('coupon_starts_at');
            }

            if (! Schema::hasColumn('plans', 'coupon_access_months')) {
                $table->unsignedInteger('coupon_access_months')->nullable()->after('coupon_expires_at');
            }

            if (! Schema::hasColumn('plans', 'coupon_access_starts_at')) {
                $table->dateTime('coupon_access_starts_at')->nullable()->after('coupon_access_months');
            }

            if (! Schema::hasColumn('plans', 'coupon_access_ends_at')) {
                $table->dateTime('coupon_access_ends_at')->nullable()->after('coupon_access_starts_at');
            }
        });
    }
};
