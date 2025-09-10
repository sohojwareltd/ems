<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic Plan',
                'description' => 'Perfect for individuals getting started',
                'price' => 9.99, // $9.99 in cents
                'currency' => 'usd',
                'interval' => 'month',
                'interval_count' => 1,
                'trial_period_days' => 7,
                'active' => true,
                'features' => [
                    'Access to basic features',
                    'Email support',
                    '5GB storage',
                    'Basic analytics'
                ]
            ],
            [
                'name' => 'Pro Plan',
                'description' => 'Best for professionals and small teams',
                'price' => 19.99, // $19.99 in cents
                'currency' => 'usd',
                'interval' => 'month',
                'interval_count' => 1,
                'trial_period_days' => 14,
                'active' => true,
                'features' => [
                    'All basic features',
                    'Priority support',
                    '50GB storage',
                    'Advanced analytics',
                    'Team collaboration',
                    'API access'
                ]
            ],
            [
                'name' => 'Enterprise Plan',
                'description' => 'Designed for large organizations',
                'price' => 49.99, // $49.99 in cents
                'currency' => 'usd',
                'interval' => 'month',
                'interval_count' => 1,
                'trial_period_days' => 30,
                'active' => true,
                'features' => [
                    'All pro features',
                    '24/7 phone support',
                    'Unlimited storage',
                    'Custom integrations',
                    'Advanced security',
                    'Dedicated account manager',
                    'Custom reporting'
                ]
            ],
            [
                'name' => 'Basic Annual',
                'description' => 'Basic plan billed annually with 2 months free',
                'price' => 99.90, // $99.90 in cents (10 months price for 12 months)
                'currency' => 'usd',
                'interval' => 'year',
                'interval_count' => 1,
                'trial_period_days' => 7,
                'active' => true,
                'features' => [
                    'Access to basic features',
                    'Email support',
                    '5GB storage',
                    'Basic analytics',
                    '2 months free'
                ]
            ],
            [
                'name' => 'Pro Annual',
                'description' => 'Pro plan billed annually with 2 months free',
                'price' => 199.90, // $199.90 in cents (10 months price for 12 months)
                'currency' => 'usd',
                'interval' => 'year',
                'interval_count' => 1,
                'trial_period_days' => 14,
                'active' => true,
                'features' => [
                    'All basic features',
                    'Priority support',
                    '50GB storage',
                    'Advanced analytics',
                    'Team collaboration',
                    'API access',
                    '2 months free'
                ]
            ],
            [
                'name' => 'Starter Plan',
                'description' => 'Free plan for testing and evaluation',
                'price' => 0, // Free
                'currency' => 'usd',
                'interval' => 'month',
                'interval_count' => 1,
                'trial_period_days' => null,
                'active' => true,
                'features' => [
                    'Limited features',
                    'Community support',
                    '1GB storage',
                    'Basic access'
                ]
            ]
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}
