<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Starting Product Seeder...');
        
        try {
            // Read products from local JSON file
            $jsonPath = public_path('json/products.json');
            
            if (file_exists($jsonPath)) {
                $jsonContent = file_get_contents($jsonPath);
                $apiProducts = json_decode($jsonContent, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('Invalid JSON format: ' . json_last_error_msg());
                }
                
                $categories = Category::all()->keyBy('slug');
                $brands = Brand::all();
                
                if ($categories->isEmpty()) {
                    throw new \Exception('No categories found. Please run CategorySeeder first.');
                }
                
                if ($brands->isEmpty()) {
                    throw new \Exception('No brands found. Please run BrandSeeder first.');
                }
                
                $this->command->info("Found " . count($apiProducts) . " products in JSON file");
                $this->command->info("Available categories: " . $categories->pluck('name')->implode(', '));
                $this->command->info("Available brands: " . $brands->pluck('name')->implode(', '));
                
                $createdCount = 0;
                $skippedCount = 0;
                $updatedCount = 0;
                
                foreach ($apiProducts as $index => $apiProduct) {
                    $this->command->line("Processing product " . ($index + 1) . "/" . count($apiProducts) . ": {$apiProduct['title']}");
                    
                    try {
                        $product = $this->createProductFromApiData($apiProduct, $categories, $brands);
                        if ($product->wasRecentlyCreated) {
                            $createdCount++;
                        } else {
                            $updatedCount++;
                        }
                    } catch (\Exception $e) {
                        $this->command->warn("⚠️  Skipped product '{$apiProduct['title']}': " . $e->getMessage());
                        $skippedCount++;
                    }
                }
                
                $this->command->info("✅ Products seeded successfully!");
                $this->command->info("📊 Created: {$createdCount}, Updated: {$updatedCount}, Skipped: {$skippedCount}");
                
            } else {
                $this->command->warn("⚠️  JSON file not found at: {$jsonPath}");
                $this->command->info("🔄 Falling back to default products...");
                $this->seedDefaultProducts();
            }
        } catch (\Exception $e) {
            $this->command->error("❌ Error processing products from JSON: " . $e->getMessage());
            $this->command->info("🔄 Falling back to default products...");
            $this->seedDefaultProducts();
        }
    }
    
    /**
     * Create a product from API data
     */
    private function createProductFromApiData(array $apiProduct, $categories, $brands): Product
    {
        // Validate required fields
        if (empty($apiProduct['title']) || empty($apiProduct['slug'])) {
            throw new \Exception('Missing required fields: title or slug');
        }
        
        // Find category
        $categorySlug = $apiProduct['category']['slug'] ?? '';
        $category = $categories->get($categorySlug);
        if (!$category) {
            throw new \Exception("Category not found: " . ($categorySlug ?: 'unknown'));
        }
        
        // Randomly select a brand
        $brand = $brands->random();
        
        // Use first image as thumbnail (store URL directly)
        $thumbnail = !empty($apiProduct['images'][0]) ? $apiProduct['images'][0] : null;
        
        // Use remaining images as gallery (store URLs directly)
        $gallery = [];
        if (!empty($apiProduct['images'])) {
            $gallery = array_slice($apiProduct['images'], 1, 3); // Limit to 3 additional images
        }
        
        // Determine if product should have variants (35% chance for clothes/shoes, 20% for others)
        $variantChance = in_array($apiProduct['category']['name'], ['Clothes', 'Shoes']) ? 35 : 20;
        $hasVariants = rand(1, 100) <= $variantChance;
        
        // Generate variants if needed
        $variants = [];
        $options = [];
        
        if ($hasVariants) {
            $options = $this->generateOptions($apiProduct['category']['name']);
            $variants = $this->generateVariants($apiProduct, $options);
        }
        
        // Generate realistic pricing based on category
        $basePrice = $this->generateRealisticPrice($apiProduct['category']['name']);
        
        $comparePrice = $this->generateComparePrice($basePrice);
        $costPrice = $this->generateCostPrice($basePrice);
        
        // Create the product
        return Product::firstOrCreate(
            ['slug' => $apiProduct['slug']],
            [
                'name' => $apiProduct['title'],
                'slug' => $apiProduct['slug'],
                'description' => $apiProduct['description'],
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'thumbnail' => $thumbnail,
                'gallery' => $gallery,
                'price' => $basePrice,
                'compare_at_price' => $comparePrice,
                'cost_per_item' => $costPrice,
                'sku' => $this->generateSku($apiProduct['title']),
                'barcode' => $this->generateBarcode(),
                'stock' => rand(5, 100),
                'status' => 'active',
                'published_at' => now(),
                'tags' => $this->generateTags($apiProduct['title'], $apiProduct['category']['name']),
                'options' => $options,
                'variants' => $variants,
                'meta_title' => $apiProduct['title'],
                'meta_description' => Str::limit($apiProduct['description'], 160),
                'meta_keywords' => implode(', ', $this->generateTags($apiProduct['title'], $apiProduct['category']['name'])),
                'has_variants' => $hasVariants,
            ]
        );
    }
    
    /**
     * Download image from URL and store locally
     */
    private function downloadImage(string $imageUrl, string $folder): ?string
    {
        try {
            $response = Http::timeout(10)->get($imageUrl);
            
            if ($response->successful()) {
                $fileName = basename(parse_url($imageUrl, PHP_URL_PATH));
                $fileName = time() . '_' . Str::random(8) . '_' . $fileName;
                $filePath = "public/{$folder}/{$fileName}";
                
                // Ensure directory exists
                $directory = storage_path("app/public/{$folder}");
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                // Save the image
                Storage::put($filePath, $response->body());
                
                return "{$folder}/{$fileName}";
            }
        } catch (\Exception $e) {
            // Don't throw exception, just return null
            return null;
        }
        
        return null;
    }
    
    /**
     * Generate product options based on category
     */
    private function generateOptions(string $categoryName): array
    {
        $optionsMap = [
            'Clothes' => ['Size', 'Color'],
            'Shoes' => ['Size', 'Color'],
            'Electronics' => ['Color', 'Storage'],
            'Furniture' => ['Color', 'Material'],
            'Miscellaneous' => ['Color', 'Size'],
        ];
        
        return $optionsMap[$categoryName] ?? ['Color', 'Size'];
    }
    
    /**
     * Generate variants for a product
     */
    private function generateVariants(array $apiProduct, array $options): array
    {
        $variants = [];
        $basePrice = $this->generateRealisticPrice($apiProduct['category']['name']);
        
        // Generate 2-4 variants
        $variantCount = rand(2, 4);
        
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        $colors = ['Black', 'White', 'Blue', 'Red', 'Green', 'Gray', 'Navy', 'Brown', 'Pink', 'Yellow'];
        $storages = ['32GB', '64GB', '128GB', '256GB', '512GB'];
        $materials = ['Leather', 'Cotton', 'Polyester', 'Wool', 'Denim', 'Silk', 'Linen'];
        
        $usedCombinations = [];
        
        for ($i = 0; $i < $variantCount; $i++) {
            $attributes = [];
            
            foreach ($options as $option) {
                switch ($option) {
                    case 'Size':
                        $attributes[$option] = $sizes[array_rand($sizes)];
                        break;
                    case 'Color':
                        $attributes[$option] = $colors[array_rand($colors)];
                        break;
                    case 'Storage':
                        $attributes[$option] = $storages[array_rand($storages)];
                        break;
                    case 'Material':
                        $attributes[$option] = $materials[array_rand($materials)];
                        break;
                }
            }
            
            // Ensure unique combinations
            $combinationKey = implode('-', array_values($attributes));
            if (in_array($combinationKey, $usedCombinations)) {
                $i--; // Retry this iteration
                continue;
            }
            $usedCombinations[] = $combinationKey;
            
            // Generate variant SKU
            $variantSku = $this->generateSku($apiProduct['title']) . '-' . Str::upper(implode('-', array_values($attributes)));
            
            // Slightly vary the price based on attributes
            $variantPrice = $this->calculateVariantPrice($basePrice, $attributes);
            
            $variants[] = [
                'sku' => $variantSku,
                'attributes' => $attributes,
                'price' => round($variantPrice, 2),
                'stock' => rand(1, 25),
                'image' => null, // Could download variant-specific images if available
            ];
        }
        
        return $variants;
    }
    
    /**
     * Calculate variant price based on attributes
     */
    private function calculateVariantPrice(float $basePrice, array $attributes): float
    {
        $price = $basePrice;
        
        // Size pricing adjustments (percentage-based to avoid extreme values)
        if (isset($attributes['Size'])) {
            switch ($attributes['Size']) {
                case 'XS':
                    $price *= 0.95; // 5% discount for XS
                    break;
                case 'S':
                    $price *= 0.98; // 2% discount for S
                    break;
                case 'L':
                    $price *= 1.02; // 2% premium for L
                    break;
                case 'XL':
                    $price *= 1.05; // 5% premium for XL
                    break;
                case 'XXL':
                    $price *= 1.08; // 8% premium for XXL
                    break;
            }
        }
        
        // Storage pricing adjustments
        if (isset($attributes['Storage'])) {
            switch ($attributes['Storage']) {
                case '64GB':
                    $price += rand(5, 15);
                    break;
                case '128GB':
                    $price += rand(15, 30);
                    break;
                case '256GB':
                    $price += rand(30, 50);
                    break;
                case '512GB':
                    $price += rand(50, 80);
                    break;
            }
        }
        
        // Material pricing adjustments
        if (isset($attributes['Material'])) {
            switch ($attributes['Material']) {
                case 'Leather':
                case 'Silk':
                    $price += rand(10, 25);
                    break;
                case 'Wool':
                    $price += rand(5, 15);
                    break;
            }
        }
        
        // Ensure minimum price based on product type
        $minPrice = $this->getMinimumPrice($basePrice);
        return max($minPrice, round($price, 2));
    }
    
    /**
     * Get minimum price based on base price
     */
    private function getMinimumPrice(float $basePrice): float
    {
        // Only apply minimum if the price is unreasonably low
        if ($basePrice < 1) {
            return 1.99; // Minimum $1.99 for very cheap items
        } elseif ($basePrice < 2) {
            return 2.99; // Minimum $2.99 for cheap items
        } elseif ($basePrice < 5) {
            return $basePrice * 0.9; // Allow 10% reduction for very low prices
        } else {
            return $basePrice * 0.8; // Allow 20% reduction for normal prices
        }
    }
    
    /**
     * Generate realistic price based on category
     */
    private function generateRealisticPrice(string $categoryName): float
    {
        $priceRanges = [
            'Clothes' => [15, 120],      // $15-$120 for clothes
            'Shoes' => [25, 200],        // $25-$200 for shoes
            'Electronics' => [50, 800],  // $50-$800 for electronics
            'Furniture' => [80, 1500],   // $80-$1500 for furniture
            'Miscellaneous' => [5, 100], // $5-$100 for misc items
        ];
        
        $range = $priceRanges[$categoryName] ?? [10, 100];
        $price = rand($range[0] * 100, $range[1] * 100) / 100; // Generate price in cents then convert
        
        return round($price, 2);
    }
    
    /**
     * Generate compare price (original price)
     */
    private function generateComparePrice(float $basePrice): ?float
    {
        // 50% chance to have a compare price
        if (rand(1, 100) <= 50) {
            $markup = rand(15, 40) / 100; // 15-40% markup
            return round($basePrice * (1 + $markup), 2);
        }
        
        return null;
    }
    
    /**
     * Generate cost price
     */
    private function generateCostPrice(float $basePrice): float
    {
        // Realistic margin based on price range
        if ($basePrice < 10) {
            $margin = rand(30, 50) / 100; // 30-50% margin for cheap items
        } elseif ($basePrice < 25) {
            $margin = rand(40, 60) / 100; // 40-60% margin for medium items
        } elseif ($basePrice < 50) {
            $margin = rand(50, 70) / 100; // 50-70% margin for higher items
        } else {
            $margin = rand(60, 80) / 100; // 60-80% margin for expensive items
        }
        
        $costPrice = $basePrice * (1 - $margin);
        return max(1.00, round($costPrice, 2)); // Ensure minimum cost of $1
    }
    
    /**
     * Generate SKU
     */
    private function generateSku(string $title): string
    {
        $words = explode(' ', $title);
        $sku = '';
        
        foreach (array_slice($words, 0, 3) as $word) {
            $cleanWord = preg_replace('/[^a-zA-Z0-9]/', '', $word);
            $sku .= Str::upper(Str::limit($cleanWord, 3, ''));
        }
        
        return $sku . '-' . Str::random(4);
    }
    
    /**
     * Generate barcode
     */
    private function generateBarcode(): string
    {
        return strval(rand(1000000000000, 9999999999999));
    }
    
    /**
     * Generate tags
     */
    private function generateTags(string $title, string $categoryName): array
    {
        $tags = [strtolower($categoryName)];
        
        // Extract words from title
        $words = explode(' ', strtolower($title));
        foreach (array_slice($words, 0, 4) as $word) {
            $cleanWord = preg_replace('/[^a-z]/', '', $word);
            if (strlen($cleanWord) > 2) {
                $tags[] = $cleanWord;
            }
        }
        
        // Add category-specific tags
        $categoryTags = [
            'Clothes' => ['fashion', 'style', 'clothing'],
            'Shoes' => ['footwear', 'shoes', 'comfort'],
            'Electronics' => ['tech', 'gadget', 'electronic'],
            'Furniture' => ['home', 'furniture', 'decor'],
            'Miscellaneous' => ['accessory', 'misc', 'lifestyle'],
        ];
        
        if (isset($categoryTags[$categoryName])) {
            $tags = array_merge($tags, $categoryTags[$categoryName]);
        }
        
        return array_unique($tags);
    }
    
    /**
     * Fallback method to seed default products if JSON fails
     */
    private function seedDefaultProducts(): void
    {
        $categories = Category::all();
        $brands = Brand::all();
        
        if ($categories->isEmpty() || $brands->isEmpty()) {
            $this->command->error('❌ Categories or Brands not found. Please run CategorySeeder and BrandSeeder first.');
            return;
        }
        
        $products = [
            [
                'name' => 'Classic Black T-Shirt',
                'slug' => 'classic-black-t-shirt',
                'description' => 'A timeless black t-shirt made from 100% organic cotton. Perfect for everyday wear and casual occasions.',
                'category' => 'T-Shirts',
                'brand' => 'Nike',
                'price' => 19.99,
                'has_variants' => true,
            ],
            [
                'name' => 'White Running Shoes',
                'slug' => 'white-running-shoes',
                'description' => 'Lightweight running shoes for everyday training. Comfortable and durable for all your athletic needs.',
                'category' => 'Shoes',
                'brand' => 'Adidas',
                'price' => 59.99,
                'has_variants' => true,
            ],
        ];
        
        $createdCount = 0;
        
        foreach ($products as $product) {
            $category = $categories->where('name', $product['category'])->first();
            $brand = $brands->where('name', $product['brand'])->first();
            
            if ($category && $brand) {
                Product::create([
                    'name' => $product['name'],
                    'slug' => $product['slug'],
                    'description' => $product['description'],
                    'category_id' => $category->id,
                    'brand_id' => $brand->id,
                    'price' => $product['price'],
                    'compare_at_price' => $product['price'] * 1.2,
                    'cost_per_item' => $product['price'] * 0.6,
                    'sku' => Str::upper(Str::slug($product['name'], '-')) . '-001',
                    'barcode' => strval(rand(1000000000000, 9999999999999)),
                    'stock' => rand(10, 100),
                    'status' => 'active',
                    'published_at' => now(),
                    'tags' => [strtolower($product['category']), strtolower($product['brand'])],
                    'options' => $product['has_variants'] ? ['Color', 'Size'] : [],
                    'variants' => $product['has_variants'] ? [] : [],
                    'meta_title' => $product['name'],
                    'meta_description' => $product['description'],
                    'meta_keywords' => strtolower($product['name']) . ', ' . strtolower($product['category']),
                    'has_variants' => $product['has_variants'],
                ]);
                $createdCount++;
            }
        }
        
        $this->command->info("✅ Default products seeded successfully! Created: {$createdCount}");
    }
} 