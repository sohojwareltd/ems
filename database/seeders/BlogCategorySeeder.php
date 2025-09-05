<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogCategory;

class BlogCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Tech News',
                'slug' => 'tech-news',
                'description' => 'Latest updates and news from the world of technology.',
                'image' => 'https://picsum.photos/400/300?random=10',
                'meta_title' => 'Tech News',
                'meta_description' => 'Stay updated with the latest tech news.',
                'meta_keywords' => 'tech, news, gadgets, updates',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Ecommerce Tips',
                'slug' => 'ecommerce-tips',
                'description' => 'Tips and tricks for running a successful online store.',
                'image' => 'https://picsum.photos/400/300?random=11',
                'meta_title' => 'Ecommerce Tips',
                'meta_description' => 'Grow your online business with our expert tips.',
                'meta_keywords' => 'ecommerce, tips, business, online store',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Product Reviews',
                'slug' => 'product-reviews',
                'description' => 'Honest reviews of the latest products in our store.',
                'image' => 'https://picsum.photos/400/300?random=12',
                'meta_title' => 'Product Reviews',
                'meta_description' => 'Read reviews before you buy.',
                'meta_keywords' => 'reviews, products, gadgets, feedback',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'How-To Guides',
                'slug' => 'how-to-guides',
                'description' => 'Step-by-step guides to help you get the most out of our products.',
                'image' => 'https://picsum.photos/400/300?random=13',
                'meta_title' => 'How-To Guides',
                'meta_description' => 'Learn how to use our products with these guides.',
                'meta_keywords' => 'how-to, guides, tutorials, help',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Industry Trends',
                'slug' => 'industry-trends',
                'description' => 'Latest trends and insights from the retail and ecommerce industry.',
                'image' => 'https://picsum.photos/400/300?random=14',
                'meta_title' => 'Industry Trends',
                'meta_description' => 'Stay ahead with the latest industry trends.',
                'meta_keywords' => 'trends, industry, retail, ecommerce, insights',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $category) {
            BlogCategory::create($category);
        }
    }
}
