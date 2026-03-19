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
        Schema::table('plans', function (Blueprint $table) {
            $table->boolean('is_coupon_enabled')->default(false)->after('active');
            $table->string('coupon_code')->nullable()->after('is_coupon_enabled');
            $table->unsignedInteger('coupon_max_uses')->nullable()->after('coupon_code');
            $table->unsignedInteger('coupon_total_used')->default(0)->after('coupon_max_uses');

            $table->index('is_coupon_enabled');
            $table->index('coupon_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropIndex(['is_coupon_enabled']);
            $table->dropIndex(['coupon_code']);

            $table->dropColumn([
                'is_coupon_enabled',
                'coupon_code',
                'coupon_max_uses',
                'coupon_total_used',
            ]);
        });
    }
};
