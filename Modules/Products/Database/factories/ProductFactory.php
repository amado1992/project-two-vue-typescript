<?php

namespace Modules\Products\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Products\Entities\Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'cost_price' => $this->faker->randomNumber(3),
            'daily_price' => $this->faker->randomNumber(3),
            'weekly_price' => $this->faker->randomNumber(3),
            'biweekly_price' => $this->faker->randomNumber(3),
            'monthly_price' => $this->faker->randomNumber(3),
            'replacement_price' => $this->faker->randomNumber(3),
            'tax' => $this->faker->randomNumber(3)
        ];
    }
}

