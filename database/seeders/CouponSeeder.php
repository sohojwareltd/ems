<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        Coupon::create([
            'code' => 'WELCOME10',
            'type' => 'percent',
            'value' => 10,
            'max_uses' => 100,
            'used' => 0,
            'min_order' => 50,
            'starts_at' => Carbon::now()->subDays(1),
            'ends_at' => Carbon::now()->addMonth(),
            'is_active' => true,
        ]);
        Coupon::create([
            'code' => 'FREESHIP',
            'type' => 'fixed',
            'value' => 5,
            'max_uses' => null,
            'used' => 0,
            'min_order' => 0,
            'starts_at' => Carbon::now()->subDays(5),
            'ends_at' => Carbon::now()->addDays(10),
            'is_active' => true,
        ]);
        Coupon::create([
            'code' => 'SUMMER20',
            'type' => 'percent',
            'value' => 20,
            'max_uses' => 50,
            'used' => 0,
            'min_order' => 100,
            'starts_at' => Carbon::now()->subDays(2),
            'ends_at' => Carbon::now()->addDays(20),
            'is_active' => true,
        ]);
        Coupon::create([
            'code' => 'EXPIRED',
            'type' => 'fixed',
            'value' => 15,
            'max_uses' => 10,
            'used' => 5,
            'min_order' => 30,
            'starts_at' => Carbon::now()->subMonths(2),
            'ends_at' => Carbon::now()->subMonth(),
            'is_active' => false,
        ]);
    }
} 