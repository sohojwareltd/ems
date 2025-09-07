<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Qualification;

class QualificationSeeder extends Seeder
{
    public function run(): void
    {
        Qualification::factory(10)->create();
    }
}
