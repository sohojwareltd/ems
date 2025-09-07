<?php
namespace Database\Factories;

use App\Models\Qualification;
use Illuminate\Database\Eloquent\Factories\Factory;

class QualificationFactory extends Factory
{
    protected $model = Qualification::class;

    public function definition()
    {
        return [
            'title' => $this->faker->words($this->faker->numberBetween(1, 3), true),
        ];
    }
}
