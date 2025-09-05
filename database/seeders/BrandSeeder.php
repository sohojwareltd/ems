<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Nike',
                'slug' => 'nike',
                'description' => 'Innovative sportswear and footwear brand.',
            ],
            [
                'name' => 'Adidas',
                'slug' => 'adidas',
                'description' => 'Leading brand in sports apparel and shoes.',
            ],
            [
                'name' => 'Puma',
                'slug' => 'puma',
                'description' => 'Sport-inspired lifestyle products.',
            ],
            [
                'name' => 'Reebok',
                'slug' => 'reebok',
                'description' => 'Fitness and lifestyle footwear and apparel.',
            ],
            [
                'name' => 'Under Armour',
                'slug' => 'under-armour',
                'description' => 'Performance apparel, footwear, and accessories.',
            ],
        ];
        foreach ($brands as $brand) {
            Brand::firstOrCreate(['slug' => $brand['slug']], $brand);
        }
    }
} 