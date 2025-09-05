<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Support\Str;

class EternaReadsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create categories using firstOrCreate to avoid duplicates
        $categories = [
            ['name' => 'Fiction', 'slug' => 'fiction', 'description' => 'Explore imaginative worlds and compelling stories'],
            ['name' => 'Non-Fiction', 'slug' => 'non-fiction', 'description' => 'Discover real stories, knowledge, and insights'],
            ['name' => 'Children\'s Books', 'slug' => 'childrens-books', 'description' => 'Books for young readers of all ages'],
            ['name' => 'Tamil Literature', 'slug' => 'tamil-literature', 'description' => 'Rich collection of Tamil literary works'],
            ['name' => 'Audiobooks', 'slug' => 'audiobooks', 'description' => 'Listen to your favorite books anywhere'],
            ['name' => 'Gift Boxes', 'slug' => 'gift-boxes', 'description' => 'Curated collections perfect for any occasion'],
            ['name' => 'Mystery & Thriller', 'slug' => 'mystery-thriller', 'description' => 'Suspenseful reads that keep you guessing'],
            ['name' => 'Romance', 'slug' => 'romance', 'description' => 'Love stories that warm the heart'],
            ['name' => 'Science Fiction', 'slug' => 'science-fiction', 'description' => 'Futuristic tales and space adventures'],
            ['name' => 'Self-Help', 'slug' => 'self-help', 'description' => 'Books to inspire personal growth and development'],
            ['name' => 'Biography', 'slug' => 'biography', 'description' => 'Real stories of remarkable people'],
            ['name' => 'History', 'slug' => 'history', 'description' => 'Journey through time with historical accounts'],
            ['name' => 'Poetry', 'slug' => 'poetry', 'description' => 'Beautiful verses and lyrical expressions'],
            ['name' => 'Cooking', 'slug' => 'cooking', 'description' => 'Culinary adventures and recipe collections'],
            ['name' => 'Travel', 'slug' => 'travel', 'description' => 'Explore the world through travel literature'],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(['slug' => $categoryData['slug']], $categoryData);
        }

        // Create brands using firstOrCreate to avoid duplicates
        $brands = [
            ['name' => 'Penguin Random House', 'slug' => 'penguin-random-house', 'description' => 'World\'s leading trade book publisher'],
            ['name' => 'HarperCollins', 'slug' => 'harpercollins', 'description' => 'One of the world\'s largest publishing companies'],
            ['name' => 'Simon & Schuster', 'slug' => 'simon-schuster', 'description' => 'Major publisher of consumer books'],
            ['name' => 'Eterna Reads', 'slug' => 'eterna-reads', 'description' => 'Our own curated collection'],
            ['name' => 'Audible', 'slug' => 'audible', 'description' => 'Leading provider of premium digital spoken audio'],
            ['name' => 'Macmillan', 'slug' => 'macmillan', 'description' => 'Global trade publishing company'],
            ['name' => 'Hachette', 'slug' => 'hachette', 'description' => 'French publishing company'],
            ['name' => 'Scholastic', 'slug' => 'scholastic', 'description' => 'Children\'s book publisher'],
            ['name' => 'Bloomsbury', 'slug' => 'bloomsbury', 'description' => 'Independent publishing house'],
            ['name' => 'Faber & Faber', 'slug' => 'faber-faber', 'description' => 'Independent publishing house'],
        ];

        foreach ($brands as $brandData) {
            Brand::firstOrCreate(['slug' => $brandData['slug']], $brandData);
        }

        // Create sample products (100+ products) - only if they don't exist
        $products = [
            // Fiction Books
            [
                'name' => 'The Midnight Library',
                'slug' => 'the-midnight-library',
                'description' => 'Between life and death there is a library, and within that library, the shelves go on forever. Every book provides a chance to try another life you could have lived.',
                'category_id' => Category::where('name', 'Fiction')->first()->id,
                'brand_id' => Brand::where('name', 'Penguin Random House')->first()->id,
                'price' => 24.99,
                'stock' => 50,
                'status' => 'active',
                'sku' => 'FIC-001',
                'thumbnail' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=400&h=500&fit=crop',
            ],
            [
                'name' => 'The Hobbit',
                'slug' => 'the-hobbit',
                'description' => 'A great modern classic and the prelude to The Lord of the Rings',
                'category_id' => Category::where('name', 'Fiction')->first()->id,
                'brand_id' => Brand::where('name', 'HarperCollins')->first()->id,
                'price' => 15.99,
                'stock' => 60,
                'status' => 'active',
                'sku' => 'FIC-002',
                'thumbnail' => 'https://images.unsplash.com/photo-1541963463532-d68292c34b19?w=400&h=500&fit=crop',
            ],
            [
                'name' => 'Pride and Prejudice',
                'slug' => 'pride-and-prejudice',
                'description' => 'Jane Austen\'s classic novel of manners and marriage',
                'category_id' => Category::where('name', 'Fiction')->first()->id,
                'brand_id' => Brand::where('name', 'Penguin Random House')->first()->id,
                'price' => 12.99,
                'stock' => 45,
                'status' => 'active',
                'sku' => 'FIC-003',
                'thumbnail' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=400&h=500&fit=crop',
            ],
            [
                'name' => '1984',
                'slug' => '1984',
                'description' => 'George Orwell\'s dystopian masterpiece',
                'category_id' => Category::where('name', 'Fiction')->first()->id,
                'brand_id' => Brand::where('name', 'Penguin Random House')->first()->id,
                'price' => 14.99,
                'stock' => 40,
                'status' => 'active',
                'sku' => 'FIC-004',
                'thumbnail' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=400&h=500&fit=crop',
            ],
            [
                'name' => 'The Great Gatsby',
                'slug' => 'the-great-gatsby',
                'description' => 'F. Scott Fitzgerald\'s Jazz Age masterpiece',
                'category_id' => Category::where('name', 'Fiction')->first()->id,
                'brand_id' => Brand::where('name', 'Simon & Schuster')->first()->id,
                'price' => 13.99,
                'stock' => 35,
                'status' => 'active',
                'sku' => 'FIC-005',
                'thumbnail' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=500&fit=crop',
            ],

            // Self-Help Books
            [
                'name' => 'Atomic Habits',
                'slug' => 'atomic-habits',
                'description' => 'Tiny Changes, Remarkable Results: An Easy & Proven Way to Build Good Habits & Break Bad Ones',
                'category_id' => Category::where('name', 'Self-Help')->first()->id,
                'brand_id' => Brand::where('name', 'Penguin Random House')->first()->id,
                'price' => 19.99,
                'stock' => 75,
                'status' => 'active',
                'sku' => 'SELF-001',
                'thumbnail' => 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?w=400&h=500&fit=crop',
            ],
            [
                'name' => 'The Power of Now',
                'slug' => 'the-power-of-now',
                'description' => 'A guide to spiritual enlightenment',
                'category_id' => Category::where('name', 'Self-Help')->first()->id,
                'brand_id' => Brand::where('name', 'Penguin Random House')->first()->id,
                'price' => 16.99,
                'stock' => 55,
                'status' => 'active',
                'sku' => 'SELF-002',
                'thumbnail' => 'https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?w=400&h=500&fit=crop',
            ],
            [
                'name' => 'Think and Grow Rich',
                'slug' => 'think-and-grow-rich',
                'description' => 'Napoleon Hill\'s classic on success principles',
                'category_id' => Category::where('name', 'Self-Help')->first()->id,
                'brand_id' => Brand::where('name', 'Simon & Schuster')->first()->id,
                'price' => 11.99,
                'stock' => 65,
                'status' => 'active',
                'sku' => 'SELF-003',
                'thumbnail' => 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?w=400&h=500&fit=crop',
            ],

            // Mystery & Thriller
            [
                'name' => 'The Silent Patient',
                'slug' => 'the-silent-patient',
                'description' => 'A psychological thriller about a woman who shoots her husband and then never speaks again',
                'category_id' => Category::where('name', 'Mystery & Thriller')->first()->id,
                'brand_id' => Brand::where('name', 'Simon & Schuster')->first()->id,
                'price' => 22.99,
                'stock' => 45,
                'status' => 'active',
                'sku' => 'MYST-001',
                'thumbnail' => 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?w=400&h=500&fit=crop',
            ],
            [
                'name' => 'Gone Girl',
                'slug' => 'gone-girl',
                'description' => 'A psychological thriller about a woman who disappears on her fifth wedding anniversary',
                'category_id' => Category::where('name', 'Mystery & Thriller')->first()->id,
                'brand_id' => Brand::where('name', 'Penguin Random House')->first()->id,
                'price' => 18.99,
                'stock' => 50,
                'status' => 'active',
                'sku' => 'MYST-002',
                'thumbnail' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=400&h=500&fit=crop',
            ],

            // Romance
            [
                'name' => 'The Seven Husbands of Evelyn Hugo',
                'slug' => 'the-seven-husbands-of-evelyn-hugo',
                'description' => 'A reclusive Hollywood legend reveals her life story to an unknown journalist',
                'category_id' => Category::where('name', 'Romance')->first()->id,
                'brand_id' => Brand::where('name', 'Simon & Schuster')->first()->id,
                'price' => 18.99,
                'stock' => 55,
                'status' => 'active',
                'sku' => 'ROM-001',
                'thumbnail' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=400&h=500&fit=crop',
            ],
            [
                'name' => 'The Notebook',
                'slug' => 'the-notebook',
                'description' => 'Nicholas Sparks\' classic love story',
                'category_id' => Category::where('name', 'Romance')->first()->id,
                'brand_id' => Brand::where('name', 'Hachette')->first()->id,
                'price' => 15.99,
                'stock' => 40,
                'status' => 'active',
                'sku' => 'ROM-002',
                'thumbnail' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=500&fit=crop',
            ],

            // Audiobooks
            [
                'name' => 'The Midnight Library - Audiobook',
                'slug' => 'the-midnight-library-audiobook',
                'description' => 'Narrated by Carey Mulligan, this audiobook brings the magical story to life',
                'category_id' => Category::where('name', 'Audiobooks')->first()->id,
                'brand_id' => Brand::where('name', 'Audible')->first()->id,
                'price' => 29.99,
                'stock' => 100,
                'status' => 'active',
                'sku' => 'AUDIO-001',
                'thumbnail' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=500&fit=crop',
            ],
            [
                'name' => 'Atomic Habits - Audiobook',
                'slug' => 'atomic-habits-audiobook',
                'description' => 'Narrated by the author James Clear, this audiobook helps you build better habits',
                'category_id' => Category::where('name', 'Audiobooks')->first()->id,
                'brand_id' => Brand::where('name', 'Audible')->first()->id,
                'price' => 24.99,
                'stock' => 80,
                'status' => 'active',
                'sku' => 'AUDIO-002',
                'thumbnail' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=500&fit=crop',
            ],
            [
                'name' => 'The Hobbit - Audiobook',
                'slug' => 'the-hobbit-audiobook',
                'description' => 'Narrated by Rob Inglis, this audiobook brings Tolkien\'s world to life',
                'category_id' => Category::where('name', 'Audiobooks')->first()->id,
                'brand_id' => Brand::where('name', 'Audible')->first()->id,
                'price' => 34.99,
                'stock' => 60,
                'status' => 'active',
                'sku' => 'AUDIO-003',
                'thumbnail' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=500&fit=crop',
            ],

            // Gift Boxes
            [
                'name' => 'Cozy Reading Gift Box',
                'slug' => 'cozy-reading-gift-box',
                'description' => 'Perfect gift for book lovers: includes a bestselling book, organic tea, scented candle, and a beautiful bookmark',
                'category_id' => Category::where('name', 'Gift Boxes')->first()->id,
                'brand_id' => Brand::where('name', 'Eterna Reads')->first()->id,
                'price' => 49.99,
                'stock' => 25,
                'status' => 'active',
                'sku' => 'GIFT-001',
                'thumbnail' => 'https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?w=400&h=500&fit=crop',
            ],
            [
                'name' => 'Mystery Lover\'s Collection',
                'slug' => 'mystery-lovers-collection',
                'description' => 'Three bestselling mystery novels in a beautiful collector\'s box',
                'category_id' => Category::where('name', 'Gift Boxes')->first()->id,
                'brand_id' => Brand::where('name', 'Eterna Reads')->first()->id,
                'price' => 39.99,
                'stock' => 30,
                'status' => 'active',
                'sku' => 'GIFT-002',
                'thumbnail' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=400&h=500&fit=crop',
            ],
            [
                'name' => 'Romance Reader\'s Delight',
                'slug' => 'romance-readers-delight',
                'description' => 'A collection of romantic novels with chocolate and wine',
                'category_id' => Category::where('name', 'Gift Boxes')->first()->id,
                'brand_id' => Brand::where('name', 'Eterna Reads')->first()->id,
                'price' => 44.99,
                'stock' => 20,
                'status' => 'active',
                'sku' => 'GIFT-003',
                'thumbnail' => 'https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?w=400&h=500&fit=crop',
            ],
        ];

        // Generate more products to reach 100+
        $fictionTitles = [
            'To Kill a Mockingbird', 'The Catcher in the Rye', 'Lord of the Flies', 'Animal Farm', 'Brave New World',
            'The Alchemist', 'The Little Prince', 'The Book Thief', 'The Kite Runner', 'Life of Pi',
            'The Curious Incident of the Dog in the Night-Time', 'The Lovely Bones', 'Water for Elephants',
            'The Help', 'The Guernsey Literary and Potato Peel Pie Society', 'The Time Traveler\'s Wife',
            'The Memory Keeper\'s Daughter', 'The Secret Life of Bees', 'The Thirteenth Tale', 'The Shadow of the Wind'
        ];

        $selfHelpTitles = [
            'The 7 Habits of Highly Effective People', 'How to Win Friends and Influence People', 'The Art of War',
            'Meditations', 'The Republic', 'The Prince', 'The Art of Happiness', 'Man\'s Search for Meaning',
            'The Road Less Traveled', 'The Four Agreements', 'The Power of Positive Thinking', 'Awaken the Giant Within',
            'Rich Dad Poor Dad', 'The Millionaire Next Door', 'The Psychology of Money',
            'The Subtle Art of Not Giving a F*ck', 'Everything Is F*cked', '12 Rules for Life', 'Beyond Order'
        ];

        $mysteryTitles = [
            'The Girl with the Dragon Tattoo', 'The Da Vinci Code', 'Angels & Demons', 'The Lost Symbol',
            'Inferno', 'Origin', 'Digital Fortress', 'Deception Point', 'The Girl Who Played with Fire',
            'The Girl Who Kicked the Hornet\'s Nest', 'The Cuckoo\'s Calling', 'The Silkworm', 'Career of Evil',
            'Lethal White', 'Troubled Blood', 'The Ink Black Heart', 'The Running Grave', 'The Thursday Murder Club',
            'The Man Who Died Twice', 'The Bullet That Missed'
        ];

        $romanceTitles = [
            'Outlander', 'Dragonfly in Amber', 'Voyager', 'Drums of Autumn', 'The Fiery Cross',
            'A Breath of Snow and Ashes', 'An Echo in the Bone', 'Written in My Own Heart\'s Blood',
            'Go Tell the Bees That I Am Gone', 'The Wedding', 'Message in a Bottle', 'A Walk to Remember',
            'Dear John', 'The Last Song', 'Safe Haven', 'The Longest Ride', 'See Me', 'Two by Two',
            'Every Breath', 'The Wish'
        ];

        $productCounter = 6; // Start after the initial products

        // Add Fiction books
        foreach ($fictionTitles as $title) {
            $slug = Str::slug($title);
            if (!Product::where('slug', $slug)->exists()) {
                $products[] = [
                    'name' => $title,
                    'slug' => $slug,
                    'description' => 'A compelling story that will keep you engaged from start to finish.',
                    'category_id' => Category::where('name', 'Fiction')->first()->id,
                    'brand_id' => Brand::inRandomOrder()->first()->id,
                    'price' => rand(1200, 2999) / 100,
                    'stock' => rand(20, 80),
                    'status' => 'active',
                    'sku' => 'FIC-' . str_pad($productCounter, 3, '0', STR_PAD_LEFT),
                    'thumbnail' => 'https://images.unsplash.com/photo-' . ['1544947950-fa07a98d237f', '1541963463532-d68292c34b19', '1512820790803-83ca734da794', '1481627834876-b7833e8f5570', '1507003211169-0a1dd7228f2d'][array_rand([0,1,2,3,4])] . '?w=400&h=500&fit=crop',
                ];
                $productCounter++;
            }
        }

        // Add Self-Help books
        foreach ($selfHelpTitles as $title) {
            $slug = Str::slug($title);
            if (!Product::where('slug', $slug)->exists()) {
                $products[] = [
                    'name' => $title,
                    'slug' => $slug,
                    'description' => 'Transform your life with these powerful insights and practical strategies.',
                    'category_id' => Category::where('name', 'Self-Help')->first()->id,
                    'brand_id' => Brand::inRandomOrder()->first()->id,
                    'price' => rand(1500, 2500) / 100,
                    'stock' => rand(30, 90),
                    'status' => 'active',
                    'sku' => 'SELF-' . str_pad($productCounter, 3, '0', STR_PAD_LEFT),
                    'thumbnail' => 'https://images.unsplash.com/photo-' . ['1589829085413-56de8ae18c73', '1513475382585-d06e58bcb0e0', '1543002588-bfa74002ed7e'][array_rand([0,1,2])] . '?w=400&h=500&fit=crop',
                ];
                $productCounter++;
            }
        }

        // Add Mystery books
        foreach ($mysteryTitles as $title) {
            $slug = Str::slug($title);
            if (!Product::where('slug', $slug)->exists()) {
                $products[] = [
                    'name' => $title,
                    'slug' => $slug,
                    'description' => 'A thrilling mystery that will keep you guessing until the very end.',
                    'category_id' => Category::where('name', 'Mystery & Thriller')->first()->id,
                    'brand_id' => Brand::inRandomOrder()->first()->id,
                    'price' => rand(1800, 2800) / 100,
                    'stock' => rand(25, 70),
                    'status' => 'active',
                    'sku' => 'MYST-' . str_pad($productCounter, 3, '0', STR_PAD_LEFT),
                    'thumbnail' => 'https://images.unsplash.com/photo-' . ['1543002588-bfa74002ed7e', '1481627834876-b7833e8f5570'][array_rand([0,1])] . '?w=400&h=500&fit=crop',
                ];
                $productCounter++;
            }
        }

        // Add Romance books
        foreach ($romanceTitles as $title) {
            $slug = Str::slug($title);
            if (!Product::where('slug', $slug)->exists()) {
                $products[] = [
                    'name' => $title,
                    'slug' => $slug,
                    'description' => 'A beautiful love story that will warm your heart and touch your soul.',
                    'category_id' => Category::where('name', 'Romance')->first()->id,
                    'brand_id' => Brand::inRandomOrder()->first()->id,
                    'price' => rand(1400, 2400) / 100,
                    'stock' => rand(35, 75),
                    'status' => 'active',
                    'sku' => 'ROM-' . str_pad($productCounter, 3, '0', STR_PAD_LEFT),
                    'thumbnail' => 'https://images.unsplash.com/photo-' . ['1512820790803-83ca734da794', '1507003211169-0a1dd7228f2d'][array_rand([0,1])] . '?w=400&h=500&fit=crop',
                ];
                $productCounter++;
            }
        }

        // Add more audiobooks
        $audiobookTitles = [
            'Pride and Prejudice - Audiobook', '1984 - Audiobook', 'The Great Gatsby - Audiobook',
            'To Kill a Mockingbird - Audiobook', 'The Catcher in the Rye - Audiobook', 'Lord of the Flies - Audiobook',
            'Animal Farm - Audiobook', 'Brave New World - Audiobook', 'The Alchemist - Audiobook',
            'The Little Prince - Audiobook', 'The Book Thief - Audiobook', 'The Kite Runner - Audiobook',
            'Life of Pi - Audiobook', 'The Curious Incident of the Dog in the Night-Time - Audiobook',
            'The Lovely Bones - Audiobook', 'Water for Elephants - Audiobook', 'The Help - Audiobook',
            'The Guernsey Literary and Potato Peel Pie Society - Audiobook', 'The Time Traveler\'s Wife - Audiobook',
            'The Memory Keeper\'s Daughter - Audiobook'
        ];

        foreach ($audiobookTitles as $title) {
            $slug = Str::slug($title);
            if (!Product::where('slug', $slug)->exists()) {
                $products[] = [
                    'name' => $title,
                    'slug' => $slug,
                    'description' => 'Listen to this captivating story narrated by professional voice actors.',
                    'category_id' => Category::where('name', 'Audiobooks')->first()->id,
                    'brand_id' => Brand::where('name', 'Audible')->first()->id,
                    'price' => rand(2500, 3500) / 100,
                    'stock' => rand(50, 120),
                    'status' => 'active',
                    'sku' => 'AUDIO-' . str_pad($productCounter, 3, '0', STR_PAD_LEFT),
                    'thumbnail' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=500&fit=crop',
                ];
                $productCounter++;
            }
        }

        // Add more gift boxes
        $giftBoxTitles = [
            'Adventure Reader\'s Box', 'Poetry Lover\'s Collection', 'Historical Fiction Set',
            'Science Fiction Bundle', 'Children\'s Storytime Box', 'Biography Collection',
            'Travel Literature Pack', 'Cooking Book Set', 'Poetry Anthology Box',
            'Classic Literature Collection', 'Modern Fiction Bundle', 'Thriller Lover\'s Pack',
            'Romance Collection', 'Self-Help Starter Kit', 'Business Book Bundle'
        ];

        foreach ($giftBoxTitles as $title) {
            $slug = Str::slug($title);
            if (!Product::where('slug', $slug)->exists()) {
                $products[] = [
                    'name' => $title,
                    'slug' => $slug,
                    'description' => 'A carefully curated collection perfect for any book lover.',
                    'category_id' => Category::where('name', 'Gift Boxes')->first()->id,
                    'brand_id' => Brand::where('name', 'Eterna Reads')->first()->id,
                    'price' => rand(3500, 5500) / 100,
                    'stock' => rand(15, 35),
                    'status' => 'active',
                    'sku' => 'GIFT-' . str_pad($productCounter, 3, '0', STR_PAD_LEFT),
                    'thumbnail' => 'https://images.unsplash.com/photo-' . ['1513475382585-d06e58bcb0e0', '1481627834876-b7833e8f5570'][array_rand([0,1])] . '?w=400&h=500&fit=crop',
                ];
                $productCounter++;
            }
        }

        // Add Children's Books
        $childrenTitles = [
            'The Very Hungry Caterpillar', 'Where the Wild Things Are', 'Goodnight Moon',
            'The Cat in the Hat', 'Green Eggs and Ham', 'Charlotte\'s Web', 'The Lion, the Witch and the Wardrobe',
            'Harry Potter and the Philosopher\'s Stone', 'Matilda', 'Charlie and the Chocolate Factory',
            'The BFG', 'James and the Giant Peach', 'The Witches', 'Fantastic Mr. Fox',
            'The Gruffalo', 'Room on the Broom', 'The Snail and the Whale', 'Stick Man',
            'We\'re Going on a Bear Hunt', 'Dear Zoo'
        ];

        foreach ($childrenTitles as $title) {
            $slug = Str::slug($title);
            if (!Product::where('slug', $slug)->exists()) {
                $products[] = [
                    'name' => $title,
                    'slug' => $slug,
                    'description' => 'A delightful children\'s book that will spark imagination and joy.',
                    'category_id' => Category::where('name', 'Children\'s Books')->first()->id,
                    'brand_id' => Brand::inRandomOrder()->first()->id,
                    'price' => rand(800, 1800) / 100,
                    'stock' => rand(40, 100),
                    'status' => 'active',
                    'sku' => 'CHILD-' . str_pad($productCounter, 3, '0', STR_PAD_LEFT),
                    'thumbnail' => 'https://images.unsplash.com/photo-' . ['1544947950-fa07a98d237f', '1541963463532-d68292c34b19', '1512820790803-83ca734da794'][array_rand([0,1,2])] . '?w=400&h=500&fit=crop',
                ];
                $productCounter++;
            }
        }

        // Add Biography Books
        $biographyTitles = [
            'Steve Jobs', 'Elon Musk', 'Becoming', 'The Autobiography of Malcolm X',
            'Long Walk to Freedom', 'I Know Why the Caged Bird Sings', 'The Diary of a Young Girl',
            'My Life', 'Dreams from My Father', 'The Story of My Experiments with Truth',
            'Wings of Fire', 'My Life in Full', 'The Last Lecture', 'When Breath Becomes Air',
            'Educated', 'Wild', 'Into the Wild', 'The Glass Castle', 'Born a Crime',
            'Shoe Dog'
        ];

        foreach ($biographyTitles as $title) {
            $slug = Str::slug($title);
            if (!Product::where('slug', $slug)->exists()) {
                $products[] = [
                    'name' => $title,
                    'slug' => $slug,
                    'description' => 'An inspiring biography that tells the remarkable story of an extraordinary life.',
                    'category_id' => Category::where('name', 'Biography')->first()->id,
                    'brand_id' => Brand::inRandomOrder()->first()->id,
                    'price' => rand(1600, 2800) / 100,
                    'stock' => rand(25, 60),
                    'status' => 'active',
                    'sku' => 'BIO-' . str_pad($productCounter, 3, '0', STR_PAD_LEFT),
                    'thumbnail' => 'https://images.unsplash.com/photo-' . ['1481627834876-b7833e8f5570', '1507003211169-0a1dd7228f2d', '1543002588-bfa74002ed7e'][array_rand([0,1,2])] . '?w=400&h=500&fit=crop',
                ];
                $productCounter++;
            }
        }

        // Add History Books
        $historyTitles = [
            'Sapiens: A Brief History of Humankind', 'Guns, Germs, and Steel', 'The Rise and Fall of the Third Reich',
            'A People\'s History of the United States', 'The Art of War', 'The Prince',
            'The Republic', 'Meditations', 'The Histories', 'The Decline and Fall of the Roman Empire',
            'A Short History of Nearly Everything', 'The Silk Roads', 'SPQR: A History of Ancient Rome',
            'The Crusades', 'The Second World War', 'The Cold War', 'The Vietnam War',
            'The Civil War', 'The American Revolution', 'The French Revolution'
        ];

        foreach ($historyTitles as $title) {
            $slug = Str::slug($title);
            if (!Product::where('slug', $slug)->exists()) {
                $products[] = [
                    'name' => $title,
                    'slug' => $slug,
                    'description' => 'A fascinating journey through time that brings history to life.',
                    'category_id' => Category::where('name', 'History')->first()->id,
                    'brand_id' => Brand::inRandomOrder()->first()->id,
                    'price' => rand(1800, 3200) / 100,
                    'stock' => rand(20, 50),
                    'status' => 'active',
                    'sku' => 'HIST-' . str_pad($productCounter, 3, '0', STR_PAD_LEFT),
                    'thumbnail' => 'https://images.unsplash.com/photo-' . ['1481627834876-b7833e8f5570', '1507003211169-0a1dd7228f2d', '1543002588-bfa74002ed7e'][array_rand([0,1,2])] . '?w=400&h=500&fit=crop',
                ];
                $productCounter++;
            }
        }

        // Add Poetry Books
        $poetryTitles = [
            'The Complete Poems of Emily Dickinson', 'Leaves of Grass', 'The Waste Land',
            'Howl and Other Poems', 'The Collected Poems of Sylvia Plath', 'Ariel',
            'The Bell Jar', 'The Love Song of J. Alfred Prufrock', 'The Road Not Taken',
            'Stopping by Woods on a Snowy Evening', 'The Raven', 'The Tyger',
            'I Wandered Lonely as a Cloud', 'Sonnet 18', 'The Divine Comedy',
            'Paradise Lost', 'The Canterbury Tales', 'Beowulf', 'The Odyssey',
            'The Iliad'
        ];

        foreach ($poetryTitles as $title) {
            $slug = Str::slug($title);
            if (!Product::where('slug', $slug)->exists()) {
                $products[] = [
                    'name' => $title,
                    'slug' => $slug,
                    'description' => 'Beautiful poetry that touches the soul and inspires the imagination.',
                    'category_id' => Category::where('name', 'Poetry')->first()->id,
                    'brand_id' => Brand::inRandomOrder()->first()->id,
                    'price' => rand(1200, 2200) / 100,
                    'stock' => rand(30, 70),
                    'status' => 'active',
                    'sku' => 'POET-' . str_pad($productCounter, 3, '0', STR_PAD_LEFT),
                    'thumbnail' => 'https://images.unsplash.com/photo-' . ['1512820790803-83ca734da794', '1507003211169-0a1dd7228f2d', '1544947950-fa07a98d237f'][array_rand([0,1,2])] . '?w=400&h=500&fit=crop',
                ];
                $productCounter++;
            }
        }

        // Add Cooking Books
        $cookingTitles = [
            'The Joy of Cooking', 'Mastering the Art of French Cooking', 'The Professional Chef',
            'On Food and Cooking', 'Salt, Fat, Acid, Heat', 'The Food Lab',
            'How to Cook Everything', 'Essentials of Classic Italian Cooking',
            'The Silver Spoon', 'Larousse Gastronomique', 'The French Laundry Cookbook',
            'Momofuku', 'The Art of Simple Food', 'Vegetarian Cooking for Everyone',
            'The Complete Vegetarian Cookbook', 'Veganomicon', 'Thug Kitchen',
            'Oh She Glows', 'The Plant Paradox', 'The Whole30'
        ];

        foreach ($cookingTitles as $title) {
            $slug = Str::slug($title);
            if (!Product::where('slug', $slug)->exists()) {
                $products[] = [
                    'name' => $title,
                    'slug' => $slug,
                    'description' => 'Delicious recipes and culinary wisdom to transform your kitchen.',
                    'category_id' => Category::where('name', 'Cooking')->first()->id,
                    'brand_id' => Brand::inRandomOrder()->first()->id,
                    'price' => rand(2000, 3500) / 100,
                    'stock' => rand(25, 60),
                    'status' => 'active',
                    'sku' => 'COOK-' . str_pad($productCounter, 3, '0', STR_PAD_LEFT),
                    'thumbnail' => 'https://images.unsplash.com/photo-' . ['1513475382585-d06e58bcb0e0', '1543002588-bfa74002ed7e', '1481627834876-b7833e8f5570'][array_rand([0,1,2])] . '?w=400&h=500&fit=crop',
                ];
                $productCounter++;
            }
        }

        // Add Travel Books
        $travelTitles = [
            'Eat, Pray, Love', 'The Alchemist', 'Into the Wild', 'Wild',
            'A Walk in the Woods', 'The Lost City of Z', 'In Patagonia',
            'The Great Railway Bazaar', 'The Old Patagonian Express', 'Dark Star Safari',
            'The Road to Oxiana', 'Arabian Sands', 'Seven Years in Tibet',
            'The Snow Leopard', 'Travels with Charley', 'On the Road',
            'The Dharma Bums', 'Big Sur', 'Desolation Angels', 'The Electric Kool-Aid Acid Test'
        ];

        foreach ($travelTitles as $title) {
            $slug = Str::slug($title);
            if (!Product::where('slug', $slug)->exists()) {
                $products[] = [
                    'name' => $title,
                    'slug' => $slug,
                    'description' => 'Journey to distant lands and discover the world through these travel narratives.',
                    'category_id' => Category::where('name', 'Travel')->first()->id,
                    'brand_id' => Brand::inRandomOrder()->first()->id,
                    'price' => rand(1400, 2600) / 100,
                    'stock' => rand(20, 55),
                    'status' => 'active',
                    'sku' => 'TRAV-' . str_pad($productCounter, 3, '0', STR_PAD_LEFT),
                    'thumbnail' => 'https://images.unsplash.com/photo-' . ['1507003211169-0a1dd7228f2d', '1512820790803-83ca734da794', '1544947950-fa07a98d237f'][array_rand([0,1,2])] . '?w=400&h=500&fit=crop',
                ];
                $productCounter++;
            }
        }

        // Create products only if they don't exist
        foreach ($products as $productData) {
            if (!Product::where('slug', $productData['slug'])->exists()) {
                Product::create($productData);
            }
        }

        // Create blog categories using firstOrCreate
        $blogCategories = [
            ['name' => 'Book Reviews', 'slug' => 'book-reviews', 'description' => 'In-depth reviews of the latest books'],
            ['name' => 'Reading Tips', 'slug' => 'reading-tips', 'description' => 'Tips and advice for better reading'],
            ['name' => 'Author Interviews', 'slug' => 'author-interviews', 'description' => 'Exclusive interviews with authors'],
            ['name' => 'Literary News', 'slug' => 'literary-news', 'description' => 'Latest news from the literary world'],
            ['name' => 'Reading Lists', 'slug' => 'reading-lists', 'description' => 'Curated reading lists for different occasions'],
            ['name' => 'Book Club Guides', 'slug' => 'book-club-guides', 'description' => 'Discussion guides for book clubs'],
        ];

        foreach ($blogCategories as $categoryData) {
            BlogCategory::firstOrCreate(['slug' => $categoryData['slug']], $categoryData);
        }

        // Create a sample user for blog posts
        $user = User::first() ?? User::factory()->create([
            'name' => 'Eterna Reads Team',
            'email' => 'team@eternareads.com',
        ]);

        // Create sample blog posts (30+ posts) - only if they don't exist
        $blogPosts = [
            [
                'title' => '10 Must-Read Books for Summer 2024',
                'slug' => '10-must-read-books-summer-2024',
                'excerpt' => 'Discover the hottest new releases and timeless classics perfect for your summer reading list.',
                'content' => 'Summer is the perfect time to dive into a good book. Whether you\'re lounging by the pool, relaxing on the beach, or enjoying a quiet evening on your porch, these 10 books will keep you entertained all season long...',
                'blog_category_id' => BlogCategory::where('slug', 'reading-lists')->first()->id,
                'user_id' => $user->id,
                'status' => 'published',
                'is_featured' => true,
                'reading_time' => 5,
                'featured_image' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=800&h=400&fit=crop',
            ],
            [
                'title' => 'How to Build a Reading Habit That Sticks',
                'slug' => 'how-to-build-reading-habit',
                'excerpt' => 'Learn practical strategies to develop a consistent reading habit and make books a part of your daily routine.',
                'content' => 'Building a reading habit can seem daunting, especially in our busy lives. But with the right approach, anyone can develop a love for reading that lasts a lifetime...',
                'blog_category_id' => BlogCategory::where('slug', 'reading-tips')->first()->id,
                'user_id' => $user->id,
                'status' => 'published',
                'is_featured' => true,
                'reading_time' => 8,
                'featured_image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&h=400&fit=crop',
            ],
            [
                'title' => 'The Rise of Audiobooks: Why They\'re Here to Stay',
                'slug' => 'rise-of-audiobooks',
                'excerpt' => 'Explore the growing popularity of audiobooks and why they\'re becoming an essential part of modern reading.',
                'content' => 'Audiobooks have experienced unprecedented growth in recent years, transforming how we consume literature. From busy professionals to avid readers, more people are discovering the joy of listening to books...',
                'blog_category_id' => BlogCategory::where('slug', 'literary-news')->first()->id,
                'user_id' => $user->id,
                'status' => 'published',
                'is_featured' => false,
                'reading_time' => 6,
                'featured_image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&h=400&fit=crop',
            ],
            [
                'title' => 'Book Review: The Midnight Library by Matt Haig',
                'slug' => 'book-review-midnight-library',
                'excerpt' => 'An in-depth review of Matt Haig\'s bestselling novel about infinite possibilities and second chances.',
                'content' => 'The Midnight Library is a beautiful exploration of the choices we make and the lives we could have lived. Matt Haig\'s writing is both philosophical and accessible...',
                'blog_category_id' => BlogCategory::where('slug', 'book-reviews')->first()->id,
                'user_id' => $user->id,
                'status' => 'published',
                'is_featured' => true,
                'reading_time' => 7,
                'featured_image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&h=400&fit=crop',
            ],
            [
                'title' => 'Interview with Bestselling Author Sarah Johnson',
                'slug' => 'interview-sarah-johnson',
                'excerpt' => 'An exclusive interview with the author of "The Silent Echo" about her writing process and inspiration.',
                'content' => 'Sarah Johnson has been captivating readers for over a decade with her psychological thrillers. In this exclusive interview, she shares insights into her creative process...',
                'blog_category_id' => BlogCategory::where('slug', 'author-interviews')->first()->id,
                'user_id' => $user->id,
                'status' => 'published',
                'is_featured' => false,
                'reading_time' => 10,
                'featured_image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&h=400&fit=crop',
            ],
        ];

        // Generate more blog posts to reach 30+
        $blogTitles = [
            'The Best Mystery Novels of 2024', 'How to Choose Your Next Book', 'Reading vs. Listening: Which is Better?',
            'The Psychology of Reading', 'Building a Home Library on a Budget', 'Classic Books Everyone Should Read',
            'The Future of Publishing', 'Digital Reading vs. Physical Books', 'Reading for Mental Health',
            'The Art of Speed Reading', 'Children\'s Books That Adults Love Too', 'The History of the Novel',
            'Book-to-Movie Adaptations: Hits and Misses', 'The Rise of Independent Bookstores',
            'Reading in the Digital Age', 'The Benefits of Reading Aloud', 'How to Start a Book Club',
            'The Most Anticipated Books of 2024', 'Reading Challenges: Are They Worth It?',
            'The Science Behind Why We Love Stories', 'Book Cover Design: Art or Marketing?',
            'The Best Books for Different Moods', 'Reading and Empathy: The Connection',
            'The Evolution of the E-book', 'Reading as a Form of Self-Care', 'The Impact of Social Media on Reading',
            'Book Recommendations Based on Your Zodiac Sign', 'The Most Beautiful Libraries in the World',
            'Reading and Memory: How Books Help Us Remember', 'The Best Books for Travelers'
        ];

        $blogCounter = 6;
        foreach ($blogTitles as $title) {
            $slug = Str::slug($title);
            if (!BlogPost::where('slug', $slug)->exists()) {
                $blogPosts[] = [
                    'title' => $title,
                    'slug' => $slug,
                    'excerpt' => 'Discover insights, tips, and recommendations for your reading journey.',
                    'content' => 'This is a comprehensive article about ' . strtolower($title) . '. We explore various aspects and provide valuable insights for readers...',
                    'blog_category_id' => BlogCategory::inRandomOrder()->first()->id,
                    'user_id' => $user->id,
                    'status' => 'published',
                    'is_featured' => rand(0, 1),
                    'reading_time' => rand(3, 12),
                    'featured_image' => 'https://images.unsplash.com/photo-' . ['1481627834876-b7833e8f5570', '1507003211169-0a1dd7228f2d', '1518709268805-4e9042af2176', '1544947950-fa07a98d237f', '1494790108755-2616b612b786', '1472099645785-5658abf4ff4e', '1438761681033-6461ffad8d80'][array_rand([0,1,2,3,4,5,6])] . '?w=800&h=400&fit=crop',
                ];
                $blogCounter++;
            }
        }

        // Create blog posts only if they don't exist
        foreach ($blogPosts as $postData) {
            if (!BlogPost::where('slug', $postData['slug'])->exists()) {
                BlogPost::create($postData);
            }
        }

        $this->command->info('Eterna Reads sample data has been seeded successfully!');
        $this->command->info('Created ' . count($products) . ' products and ' . count($blogPosts) . ' blog posts.');
    }
} 