<?php

namespace Database\Factories;

use App\Models\PricingModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class PricingModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PricingModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
        ];
    }
}
