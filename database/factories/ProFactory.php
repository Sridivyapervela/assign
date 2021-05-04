<?php

namespace Database\Factories;

use App\Models\Pro;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pro::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' =>$this->faker->realText(30),
            'description' =>$this->faker->realText(),
        ];
    }
}
