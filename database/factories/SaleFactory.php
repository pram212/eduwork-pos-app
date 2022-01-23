<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => 'SO' . $this->faker->numberBetween(1000, 9999),
            'total_price' => $this->faker->numberBetween(5000, 100000),
            'payment' => $this->faker->numberBetween(50000, 100000),
        ];
    }
}
