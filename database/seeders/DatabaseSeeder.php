<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
            $this->call([
            ResourceSeeder::class,
            SubjectSeeder::class,
            ExamboardSeeder::class,
            QualificationSeeder::class,
            RoleTableSeeder::class,
            UserTableSeeder::class,
            EternaReadsSeeder::class,
            // BrandSeeder::class,
            // CategorySeeder::class,
            // ProductSeeder::class,
            OrderSeeder::class,
            OrderHistorySeeder::class,
            CouponSeeder::class,
            ShippingMethodSeeder::class,
            SettingsSeeder::class,
            PermissionSeeder::class,
            BlogCategorySeeder::class,
            BlogPostSeeder::class,
            SliderSeeder::class,
            FaqSeeder::class,
            CountrySeeder::class,
            MenuSeeder::class,
            PlanSeeder::class,
        ]);
    }
}
