<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {

        $now = Carbon::now();
        $settings = [
            [
                'key' => 'store',
                'value' => [
                    'name' => 'My Shop',
                    'email' => 'support@myshop.com',
                    'phone' => '+1-555-123-4567',
                    'address' => '123 Main St, City, Country',
                    'logo' => null,
                    'facebook' => 'https://facebook.com/myshop',
                    'instagram' => 'https://instagram.com/myshop',
                    'twitter' => 'https://twitter.com/myshop',
                    'currency' => 'USD',
                ]
            ],
            [
                'key' => 'seo',
                'value' => [
                    'meta_title' => 'My Shop - Best Online Store',
                    'meta_description' => 'Welcome to My Shop, your best source for quality products.',
                    'meta_keywords' => 'shop, ecommerce, online store, buy, products',
                    'google_analytics_id' => null,
                    'facebook_pixel_id' => null,
                ]
            ],
            [
                'key' => 'payments',
                'value' => [
                    'enable_stripe' => true,
                    'stripe_sandbox' => true,
                    'stripe_key' => env('STRIPE_KEY'),
                    'stripe_secret' => env('STRIPE_SECRET'),
                    'enable_paypal' => true,
                    'paypal_sandbox' => true,
                    'paypal_client_id' => env('PAYPAL_CLIENT_ID'),
                    'paypal_secret' => env('PAYPAL_SECRET'),
                ]
            ],
        ];
        foreach ($settings as $setting) {

            foreach ($setting['value'] as $key => $value) {
                
                DB::table('settings')->insert([
                    'key' => $setting['key'] . '.' . $key,
                    'value' => json_encode($value),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
