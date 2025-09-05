<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reviews = [
            [
                'name' => 'Sarah Johnson',
                'title' => 'Book Club Leader',
                'content' => 'Eterna Reads has become my go-to bookstore. Their curated selection and gift boxes are absolutely perfect for any book lover!',
                'rating' => 5,
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Michael Chen',
                'title' => 'Audiobook Enthusiast',
                'content' => 'The audiobook collection is fantastic! Perfect for my daily commute. The quality and selection are outstanding.',
                'rating' => 5,
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Emily Rodriguez',
                'title' => 'Gift Buyer',
                'content' => 'I love their gift boxes! They\'re beautifully packaged and always contain the perfect combination of books and accessories.',
                'rating' => 5,
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'David Thompson',
                'title' => 'Literature Professor',
                'content' => 'As an educator, I appreciate the thoughtful curation of books. Eterna Reads consistently offers quality literature that engages and inspires.',
                'rating' => 5,
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Lisa Wang',
                'title' => 'Book Blogger',
                'content' => 'The customer service is exceptional and their recommendations are always spot-on. A truly wonderful bookstore experience!',
                'rating' => 5,
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Robert Martinez',
                'title' => 'Parent',
                'content' => 'My kids love the children\'s book selection. The staff is knowledgeable and always helps us find the perfect books for their age.',
                'rating' => 5,
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($reviews as $review) {
            Review::create($review);
        }
    }
}
