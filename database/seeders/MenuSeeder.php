<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        Schema::disableForeignKeyConstraints();
        DB::table('menu_locations')->truncate();
        DB::table('menu_items')->truncate();
        DB::table('menus')->truncate();
        Schema::enableForeignKeyConstraints();

        // Seed menus
        $menusData = [
            [
                'id' => 2,
                'name' => 'Quick Links',
                'is_visible' => 1,
                'created_at' => '2025-07-13 21:50:18',
                'updated_at' => '2025-07-13 21:50:18'
            ],
            [
                'id' => 3,
                'name' => 'Customer Service',
                'is_visible' => 1,
                'created_at' => '2025-07-13 21:53:36',
                'updated_at' => '2025-07-13 21:53:36'
            ],
            [
                'id' => 4,
                'name' => 'Main',
                'is_visible' => 1,
                'created_at' => '2025-07-13 21:59:19',
                'updated_at' => '2025-07-13 21:59:19'
            ],
            [
                'id' => 5,
                'name' => 'Mobile Menu',
                'is_visible' => 1,
                'created_at' => '2025-07-13 22:02:26',
                'updated_at' => '2025-07-13 22:02:26'
            ]
        ];

        DB::table('menus')->insert($menusData);

        // Seed menu_items
        $menuItemsData = [
            [
                'id' => 1,
                'menu_id' => 2,
                'parent_id' => null,
                'linkable_type' => null,
                'linkable_id' => null,
                'title' => 'Shop',
                'url' => '/products',
                'target' => '_self',
                'order' => 1,
                'created_at' => '2025-07-13 21:50:55',
                'updated_at' => '2025-07-13 21:50:55'
            ],
            [
                'id' => 2,
                'menu_id' => 2,
                'parent_id' => null,
                'linkable_type' => null,
                'linkable_id' => null,
                'title' => 'Gift Boxes',
                'url' => '/products?category=gift-boxes',
                'target' => '_self',
                'order' => 2,
                'created_at' => '2025-07-13 21:51:28',
                'updated_at' => '2025-07-13 21:51:28'
            ],
            [
                'id' => 3,
                'menu_id' => 2,
                'parent_id' => null,
                'linkable_type' => null,
                'linkable_id' => null,
                'title' => 'Audiobooks',
                'url' => '/products?category=audiobooks',
                'target' => '_self',
                'order' => 3,
                'created_at' => '2025-07-13 21:51:59',
                'updated_at' => '2025-07-13 21:51:59'
            ],
            [
                'id' => 4,
                'menu_id' => 2,
                'parent_id' => null,
                'linkable_type' => null,
                'linkable_id' => null,
                'title' => 'Blog',
                'url' => '/blog',
                'target' => '_self',
                'order' => 4,
                'created_at' => '2025-07-13 21:52:25',
                'updated_at' => '2025-07-13 21:52:25'
            ],
            [
                'id' => 5,
                'menu_id' => 2,
                'parent_id' => null,
                'linkable_type' => null,
                'linkable_id' => null,
                'title' => 'About Us',
                'url' => '/about',
                'target' => '_self',
                'order' => 5,
                'created_at' => '2025-07-13 21:52:54',
                'updated_at' => '2025-07-13 21:52:54'
            ],
            [
                'id' => 6,
                'menu_id' => 3,
                'parent_id' => null,
                'linkable_type' => null,
                'linkable_id' => null,
                'title' => 'Contact Us',
                'url' => '/contact',
                'target' => '_self',
                'order' => 1,
                'created_at' => '2025-07-13 21:54:01',
                'updated_at' => '2025-07-13 21:54:01'
            ],
            [
                'id' => 7,
                'menu_id' => 3,
                'parent_id' => null,
                'linkable_type' => null,
                'linkable_id' => null,
                'title' => 'FAQ',
                'url' => '/faq',
                'target' => '_self',
                'order' => 2,
                'created_at' => '2025-07-13 21:54:21',
                'updated_at' => '2025-07-13 21:54:21'
            ],
            [
                'id' => 8,
                'menu_id' => 3,
                'parent_id' => null,
                'linkable_type' => null,
                'linkable_id' => null,
                'title' => 'Shipping Info',
                'url' => '/faq#shipping',
                'target' => '_self',
                'order' => 3,
                'created_at' => '2025-07-13 21:54:49',
                'updated_at' => '2025-07-13 21:54:49'
            ],
            [
                'id' => 9,
                'menu_id' => 3,
                'parent_id' => null,
                'linkable_type' => null,
                'linkable_id' => null,
                'title' => 'Returns',
                'url' => '/faq#returns',
                'target' => '_self',
                'order' => 4,
                'created_at' => '2025-07-13 21:55:21',
                'updated_at' => '2025-07-13 21:55:21'
            ],
            [
                'id' => 10,
                'menu_id' => 3,
                'parent_id' => null,
                'linkable_type' => null,
                'linkable_id' => null,
                'title' => 'Privacy Policy',
                'url' => '/faq#privacy',
                'target' => '_self',
                'order' => 5,
                'created_at' => '2025-07-13 21:55:58',
                'updated_at' => '2025-07-13 21:55:58'
            ],
            [
                'id' => 11,
                'menu_id' => 4,
                'parent_id' => null,
                'linkable_type' => null,
                'linkable_id' => null,
                'title' => 'Shop',
                'url' => '/products',
                'target' => '_self',
                'order' => 1,
                'created_at' => '2025-07-13 21:59:51',
                'updated_at' => '2025-07-13 22:00:35'
            ],
            [
                'id' => 12,
                'menu_id' => 4,
                'parent_id' => null,
                'linkable_type' => null,
                'linkable_id' => null,
                'title' => 'Audiobooks',
                'url' => '/products?category=audiobooks',
                'target' => '_self',
                'order' => 3,
                'created_at' => '2025-07-13 22:00:02',
                'updated_at' => '2025-07-13 22:00:35'
            ],
            [
                'id' => 13,
                'menu_id' => 4,
                'parent_id' => null,
                'linkable_type' => null,
                'linkable_id' => null,
                'title' => 'Gift Boxes',
                'url' => '/products?category=gift-boxes',
                'target' => '_self',
                'order' => 2,
                'created_at' => '2025-07-13 22:00:27',
                'updated_at' => '2025-07-13 22:00:35'
            ],
            [
                'id' => 14,
                'menu_id' => 4,
                'parent_id' => null,
                'linkable_type' => null,
                'linkable_id' => null,
                'title' => 'About',
                'url' => '/about',
                'target' => '_self',
                'order' => 4,
                'created_at' => '2025-07-13 22:00:55',
                'updated_at' => '2025-07-13 22:00:55'
            ],
            [
                'id' => 15,
                'menu_id' => 4,
                'parent_id' => null,
                'linkable_type' => null,
                'linkable_id' => null,
                'title' => 'Blog',
                'url' => '/blog',
                'target' => '_self',
                'order' => 5,
                'created_at' => '2025-07-13 22:01:23',
                'updated_at' => '2025-07-13 22:01:23'
            ],
            [
                'id' => 16,
                'menu_id' => 4,
                'parent_id' => null,
                'linkable_type' => null,
                'linkable_id' => null,
                'title' => 'Contact',
                'url' => '/contact',
                'target' => '_self',
                'order' => 6,
                'created_at' => '2025-07-13 22:01:41',
                'updated_at' => '2025-07-13 22:01:41'
            ],
            [
                'id' => 17,
                'menu_id' => 4,
                'parent_id' => null,
                'linkable_type' => null,
                'linkable_id' => null,
                'title' => 'FAQ',
                'url' => '/faq',
                'target' => '_self',
                'order' => 7,
                'created_at' => '2025-07-13 22:02:00',
                'updated_at' => '2025-07-13 22:02:00'
            ],
            [
                'id' => 18,
                'menu_id' => 5,
                'parent_id' => null,
                'linkable_type' => null,
                'linkable_id' => null,
                'title' => 'Shop',
                'url' => '/products',
                'target' => '_self',
                'order' => 1,
                'created_at' => '2025-07-13 22:02:57',
                'updated_at' => '2025-07-13 22:02:57'
            ],
            [
                'id' => 19,
                'menu_id' => 5,
                'parent_id' => null,
                'linkable_type' => null,
                'linkable_id' => null,
                'title' => 'Gift Boxes',
                'url' => '/products?category=gift-boxes',
                'target' => '_self',
                'order' => 2,
                'created_at' => '2025-07-13 22:03:16',
                'updated_at' => '2025-07-13 22:03:16'
            ],
            [
                'id' => 20,
                'menu_id' => 5,
                'parent_id' => null,
                'linkable_type' => null,
                'linkable_id' => null,
                'title' => 'Audiobooks',
                'url' => '/products?category=audiobooks',
                'target' => '_self',
                'order' => 3,
                'created_at' => '2025-07-13 22:03:28',
                'updated_at' => '2025-07-13 22:03:28'
            ],
            [
                'id' => 21,
                'menu_id' => 5,
                'parent_id' => null,
                'linkable_type' => null,
                'linkable_id' => null,
                'title' => 'About',
                'url' => '/about',
                'target' => '_self',
                'order' => 4,
                'created_at' => '2025-07-13 22:03:44',
                'updated_at' => '2025-07-13 22:03:44'
            ],
            [
                'id' => 22,
                'menu_id' => 5,
                'parent_id' => null,
                'linkable_type' => null,
                'linkable_id' => null,
                'title' => 'Blog',
                'url' => '/blog',
                'target' => '_self',
                'order' => 5,
                'created_at' => '2025-07-13 22:03:52',
                'updated_at' => '2025-07-13 22:03:52'
            ],
            [
                'id' => 23,
                'menu_id' => 5,
                'parent_id' => null,
                'linkable_type' => null,
                'linkable_id' => null,
                'title' => 'Contact',
                'url' => '/contact',
                'target' => '_self',
                'order' => 6,
                'created_at' => '2025-07-13 22:04:16',
                'updated_at' => '2025-07-13 22:04:16'
            ],
            [
                'id' => 24,
                'menu_id' => 5,
                'parent_id' => null,
                'linkable_type' => null,
                'linkable_id' => null,
                'title' => 'FAQ',
                'url' => '/faq',
                'target' => '_self',
                'order' => 7,
                'created_at' => '2025-07-13 22:04:28',
                'updated_at' => '2025-07-13 22:04:28'
            ]
        ];

        DB::table('menu_items')->insert($menuItemsData);

        // Seed menu_locations
        $menuLocationsData = [
            [
                'id' => 1,
                'menu_id' => 2,
                'location' => 'quick_links',
                'created_at' => '2025-07-13 21:53:08',
                'updated_at' => '2025-07-13 21:53:08'
            ],
            [
                'id' => 2,
                'menu_id' => 3,
                'location' => 'customer_service',
                'created_at' => '2025-07-13 21:58:52',
                'updated_at' => '2025-07-13 21:58:52'
            ],
            [
                'id' => 3,
                'menu_id' => 4,
                'location' => 'main',
                'created_at' => '2025-07-13 22:02:12',
                'updated_at' => '2025-07-13 22:02:12'
            ],
            [
                'id' => 4,
                'menu_id' => 5,
                'location' => 'mobile',
                'created_at' => '2025-07-13 22:04:50',
                'updated_at' => '2025-07-13 22:04:50'
            ]
        ];

        DB::table('menu_locations')->insert($menuLocationsData);

        $this->command->info('Menu data seeded successfully!');
    }
}
