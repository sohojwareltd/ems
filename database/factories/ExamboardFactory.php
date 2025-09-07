<?php
namespace Database\Factories;

use App\Models\Examboard;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamboardFactory extends Factory
{
    protected $model = Examboard::class;

    public function definition()
    {
        return [
            'title' => $this->faker->words($this->faker->numberBetween(1, 3), true),
        ];
    }
}
