<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['name' => 'India', 'code' => 'IN'],
            ['name' => 'United States', 'code' => 'US'],
            ['name' => 'Canada', 'code' => 'CA'],
            ['name' => 'United Kingdom', 'code' => 'GB'],
            ['name' => 'Australia', 'code' => 'AU'],
        ];

        foreach ($countries as $country) {
            Country::create($country);
        }
    }
}
