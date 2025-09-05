<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $categories = BlogCategory::all();
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create(['email' => 'blogger@example.com']);
        }

        $posts = [
            [
                'title' => 'The Future of AI in Ecommerce',
                'content' => '<p>Artificial Intelligence is transforming the ecommerce landscape in unprecedented ways. From personalized shopping experiences to automated inventory management, AI is becoming an integral part of modern online retail.</p><p>Machine learning algorithms can now predict customer behavior, optimize pricing strategies, and even automate customer service through chatbots. The future looks promising as AI continues to evolve and become more sophisticated.</p><p>Businesses that embrace AI technology early will have a significant competitive advantage in the rapidly evolving digital marketplace.</p>',
                'category' => 'Tech News',
                'excerpt' => 'Artificial Intelligence is transforming the ecommerce landscape in unprecedented ways. From personalized shopping experiences to automated inventory management, AI is becoming an integral part of modern online retail.',
                'featured_image' => 'https://picsum.photos/800/400?random=1',
                'is_featured' => true,
                'status' => 'published',
            ],
            [
                'title' => '5 Tips to Boost Your Online Sales',
                'content' => '<p>Want to increase your sales? Here are 5 proven tips that can help you boost your online revenue significantly.</p><p>First, optimize your product images and descriptions to make them more appealing to potential customers. High-quality images and detailed descriptions can make a huge difference in conversion rates.</p><p>Second, implement a customer loyalty program to encourage repeat purchases. Third, use email marketing campaigns to keep your customers engaged. Fourth, offer free shipping or discounts to reduce cart abandonment. Finally, ensure your website is mobile-friendly and loads quickly.</p>',
                'category' => 'Ecommerce Tips',
                'excerpt' => 'Want to increase your sales? Here are 5 proven tips that can help you boost your online revenue significantly.',
                'featured_image' => 'https://picsum.photos/800/400?random=2',
                'is_featured' => true,
                'status' => 'published',
            ],
            [
                'title' => 'Review: The Newest Smartwatch',
                'content' => '<p>We tested the latest smartwatch and here is what we found after weeks of intensive testing.</p><p>The new smartwatch features an impressive battery life of up to 7 days, a bright AMOLED display, and advanced health tracking capabilities. The device seamlessly integrates with both iOS and Android devices, making it accessible to a wide range of users.</p><p>While the price point is on the higher side, the quality and features justify the investment for fitness enthusiasts and tech-savvy individuals.</p>',
                'category' => 'Product Reviews',
                'excerpt' => 'We tested the latest smartwatch and here is what we found after weeks of intensive testing.',
                'featured_image' => 'https://picsum.photos/800/400?random=3',
                'is_featured' => false,
                'status' => 'published',
            ],
            [
                'title' => 'How to Set Up Your Online Store',
                'content' => '<p>Setting up an online store is easier than you think with the right tools and guidance.</p><p>Start by choosing the right ecommerce platform that fits your business needs. Consider factors like ease of use, customization options, and pricing. Next, select a domain name that reflects your brand and is easy to remember.</p><p>Design your store with a clean, professional layout that makes navigation intuitive for your customers. Don\'t forget to set up secure payment gateways and implement SSL certificates for security.</p>',
                'category' => 'How-To Guides',
                'excerpt' => 'Setting up an online store is easier than you think with the right tools and guidance.',
                'featured_image' => 'https://picsum.photos/800/400?random=4',
                'is_featured' => false,
                'status' => 'published',
            ],
            [
                'title' => 'Sustainable Shopping: The Future of Retail',
                'content' => '<p>As consumers become more environmentally conscious, sustainable shopping practices are gaining momentum in the retail industry.</p><p>Many brands are now offering eco-friendly products, using sustainable packaging materials, and implementing carbon-neutral shipping options. Consumers are increasingly willing to pay premium prices for products that align with their environmental values.</p><p>This shift towards sustainability is not just a trend but a fundamental change in how we approach consumption and retail.</p>',
                'category' => 'Industry Trends',
                'excerpt' => 'As consumers become more environmentally conscious, sustainable shopping practices are gaining momentum in the retail industry.',
                'featured_image' => 'https://picsum.photos/800/400?random=5',
                'is_featured' => true,
                'status' => 'published',
            ],
            [
                'title' => 'Mobile Commerce: Why Your Business Needs a Mobile App',
                'content' => '<p>Mobile commerce is no longer optional for businesses that want to stay competitive in today\'s digital marketplace.</p><p>With over 70% of online traffic coming from mobile devices, having a mobile-optimized website or app is crucial for reaching your target audience. Mobile apps provide a better user experience, faster loading times, and more personalized features compared to mobile websites.</p><p>Investing in mobile commerce can significantly increase your conversion rates and customer satisfaction.</p>',
                'category' => 'Tech News',
                'excerpt' => 'Mobile commerce is no longer optional for businesses that want to stay competitive in today\'s digital marketplace.',
                'featured_image' => 'https://picsum.photos/800/400?random=6',
                'is_featured' => false,
                'status' => 'published',
            ],
        ];

        foreach ($posts as $post) {
            $category = $categories->where('name', $post['category'])->first();
            if (!$category) continue;
            BlogPost::create([
                'user_id' => $user->id,
                'blog_category_id' => $category->id,
                'title' => $post['title'],
                'slug' => Str::slug($post['title']),
                'excerpt' => $post['excerpt'],
                'content' => $post['content'],
                'featured_image' => $post['featured_image'],
                'gallery' => null,
                'meta_title' => $post['title'],
                'meta_description' => $post['excerpt'],
                'meta_keywords' => strtolower(str_replace(' ', ',', $post['title'])),
                'status' => $post['status'],
                'is_featured' => $post['is_featured'],
                'allow_comments' => true,
                'published_at' => Carbon::now()->subDays(rand(1, 30)),
                'view_count' => rand(10, 500),
            ]);
        }
    }
}
