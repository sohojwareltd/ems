<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Starting Category Seeder...');
        
        try {
            // Read categories from local JSON file
            $jsonPath = public_path('json/categories.json');
            
            if (file_exists($jsonPath)) {
                $jsonContent = file_get_contents($jsonPath);
                $apiCategories = json_decode($jsonContent, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('Invalid JSON format: ' . json_last_error_msg());
                }
                
                $this->command->info("Found " . count($apiCategories) . " categories in JSON file");
                
                $createdCount = 0;
                $updatedCount = 0;
                
                foreach ($apiCategories as $index => $apiCategory) {
                    $this->command->line("Processing category " . ($index + 1) . "/" . count($apiCategories) . ": {$apiCategory['name']}");
                    
                    $category = Category::firstOrCreate(
                        ['slug' => $apiCategory['slug']],
                        [
                            'name' => $apiCategory['name'],
                            'slug' => $apiCategory['slug'],
                            'description' => $this->generateDescription($apiCategory['name']),
                            'image' => $apiCategory['image'] ?? null,
                        ]
                    );
                    
                    if ($category->wasRecentlyCreated) {
                        $createdCount++;
                    } else {
                        $updatedCount++;
                    }
                }
                
                $this->command->info("✅ Categories seeded successfully!");
                $this->command->info("📊 Created: {$createdCount}, Updated: {$updatedCount}");
                
            } else {
                $this->command->warn("⚠️  JSON file not found at: {$jsonPath}");
                $this->command->info("🔄 Falling back to default categories...");
                $this->seedDefaultCategories();
            }
        } catch (\Exception $e) {
            $this->command->error("❌ Error processing categories from JSON: " . $e->getMessage());
            $this->command->info("🔄 Falling back to default categories...");
            $this->seedDefaultCategories();
        }
    }
    
    /**
     * Generate a description based on category name
     */
    private function generateDescription(string $categoryName): string
    {
        $descriptions = [
            'Clothes' => 'Discover our curated collection of stylish and comfortable clothing for all occasions. From casual wear to formal attire, find the perfect outfit that reflects your unique style and personality.',
            'Electronics' => 'Explore the latest electronic devices and cutting-edge gadgets for modern living. Stay connected, productive, and entertained with our selection of innovative technology solutions.',
            'Furniture' => 'Transform your living space with our quality furniture collection. From elegant designs to comfortable pieces, create the home of your dreams with our carefully selected furnishings.',
            'Shoes' => 'Step out in confidence with our trendy and durable footwear collection. Whether you need casual comfort or formal elegance, our shoes are designed for every style and occasion.',
            'Miscellaneous' => 'Discover unique products and accessories that add convenience and style to your life. From practical solutions to trendy items, find everything you need in our miscellaneous collection.',
        ];
        
        return $descriptions[$categoryName] ?? "Explore our premium collection of {$categoryName} products. Quality craftsmanship meets innovative design to bring you the best selection for your needs.";
    }
    
    /**
     * Fallback method to seed default categories if JSON fails
     */
    private function seedDefaultCategories(): void
    {
        $categories = [
            [
                'name' => 'T-Shirts',
                'slug' => 't-shirts',
                'description' => 'Comfortable and stylish t-shirts for all occasions. Perfect for casual wear and everyday comfort.',
                'image' => null,
            ],
            [
                'name' => 'Shoes',
                'slug' => 'shoes',
                'description' => 'Trendy and durable shoes for men and women. Step out in style with our footwear collection.',
                'image' => null,
            ],
            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'description' => 'Complete your look with our selection of belts, hats, and other essential accessories.',
                'image' => null,
            ],
            [
                'name' => 'Hoodies',
                'slug' => 'hoodies',
                'description' => 'Warm and cozy hoodies for chilly days. Stay comfortable and stylish in any weather.',
                'image' => null,
            ],
            [
                'name' => 'Jackets',
                'slug' => 'jackets',
                'description' => 'Stylish jackets for every season. From lightweight to heavy-duty, find your perfect outerwear.',
                'image' => null,
            ],
        ];
        
        $createdCount = 0;
        
        foreach ($categories as $category) {
            $newCategory = Category::firstOrCreate(['slug' => $category['slug']], $category);
            if ($newCategory->wasRecentlyCreated) {
                $createdCount++;
            }
        }
        
        $this->command->info("✅ Default categories seeded successfully! Created: {$createdCount}");
    }
} 