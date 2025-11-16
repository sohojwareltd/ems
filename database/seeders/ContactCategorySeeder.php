<?php

namespace Database\Seeders;

use App\Models\ContactCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'General Inquiry',
                'slug' => 'general-inquiry',
                'description' => 'General questions and information requests',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Tuition',
                'slug' => 'tuition',
                'description' => 'Questions about tuition services and rates',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Technical Support',
                'slug' => 'technical-support',
                'description' => 'Technical issues and support requests',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Billing',
                'slug' => 'billing',
                'description' => 'Billing and payment related questions',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Other',
                'slug' => 'other',
                'description' => 'Other enquiries not covered by other categories',
                'sort_order' => 99,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            ContactCategory::create($category);
        }
    }
}
