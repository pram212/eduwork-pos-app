<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $firstCategory = \App\Models\Category::all()->first()->id;
        $lastCategory = \App\Models\Category::all()->last()->id;

        $firstWarehouse = \App\Models\Warehouse::all()->first()->id;
        $lastWarehouse = \App\Models\Warehouse::all()->last()->id;

        return [
            'code' => $this->faker->unique()->ean8(),
            'name' => $this->faker->word(),
            'price' => $this->faker->numberBetween(1000, 500000),
            'stock' => $this->faker->numberBetween(1, 1000),
            'category_id' => $this->faker->numberBetween($firstCategory, $lastCategory),
            'warehouse_id' => $this->faker->numberBetween($firstWarehouse, $lastWarehouse),
        ];
    }
}
