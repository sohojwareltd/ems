<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Examboard;

class ExamboardSeeder extends Seeder
{
    public function run(): void
    {
        Examboard::factory(10)->create();
    }
}
