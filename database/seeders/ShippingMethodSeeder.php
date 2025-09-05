<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ShippingMethod;

class ShippingMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [['name' => 'FedEx'], ['name' => 'UPS'], ['name' => 'USPS'], ['name' => 'DHL']];
        foreach ($methods as $method) { 
            ShippingMethod::create($method);
        }
    }
}
