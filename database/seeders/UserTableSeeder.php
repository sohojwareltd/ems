<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Only create admin if not exists
        $adminEmail = 'thisiskazi@gmail.com';
        if (!\App\Models\User::where('email', $adminEmail)->exists()) {
            \App\Models\User::create([
                'name' => 'Admin',
                'email' => $adminEmail,
                'role_id' => 1,
                'password' => bcrypt('password'),
            ]);
        }

        // Add more customer users
        \App\Models\User::factory()->count(5)->create([
            'role_id' => 2, // or assign role via relationship if using spatie/laravel-permission
        ]);
    }
}
