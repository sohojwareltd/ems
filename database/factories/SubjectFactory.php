<?php
namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    public function definition()
    {
        return [
            'title' => $this->faker->words($this->faker->numberBetween(1, 3), true),
        ];
    }
}
