<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Slider;
use Carbon\Carbon;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        $sliders = [
            [
                'title' => 'Discover Your Next Great Read',
                'description' => 'Welcome to Eterna Reads, your literary haven for physical books, audiobooks, and curated gift boxes. Immerse yourself in stories that inspire, educate, and entertain.',
                'image' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80',
                'button_text' => 'Browse Books',
                'button_url' => '/products',
                'button_color' => '#007bff',
                'position' => 'top',
                'is_active' => true,
                'sort_order' => 1,
                'starts_at' => Carbon::now()->subDays(10),
                'ends_at' => Carbon::now()->addDays(365),
            ],
            [
                'title' => 'Holiday Special: 20% Off Gift Boxes',
                'description' => 'Perfect for the book lover in your life. Our curated gift boxes feature handpicked books, literary accessories, and thoughtful surprises.',
                'image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80',
                'button_text' => 'Shop Gift Boxes',
                'button_url' => '/products?category=gift-boxes',
                'button_color' => '#28a745',
                'position' => 'top',
                'is_active' => true,
                'sort_order' => 2,
                'starts_at' => Carbon::now()->subDays(5),
                'ends_at' => Carbon::now()->addDays(30),
            ],
            [
                'title' => 'Listen to Stories Anywhere',
                'description' => 'Discover our collection of audiobooks narrated by talented voice actors. Perfect for commuting, exercising, or relaxing at home.',
                'image' => 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80',
                'button_text' => 'Listen Now',
                'button_url' => '/products?category=audiobooks',
                'button_color' => '#ffc107',
                'position' => 'top',
                'is_active' => true,
                'sort_order' => 3,
                'starts_at' => Carbon::now()->subDays(2),
                'ends_at' => Carbon::now()->addDays(365),
            ],
        ];

        foreach ($sliders as $slider) {
            Slider::create($slider);
        }
    }
}
