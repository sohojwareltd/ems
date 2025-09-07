<?php
namespace Database\Factories;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResourceFactory extends Factory
{
    protected $model = Resource::class;

    public function definition()
    {
        return [
            'title' => $this->faker->words($this->faker->numberBetween(1, 3), true),
        ];
    }
}
