<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => 'PO' . $this->faker->unique()->numberBetween(1000, 9999),
            'payment_status' => 'belum bayar',
            'acceptance_status' => 'belum diterima',
            'supplier_id' => \App\Models\Supplier::all()->first(),
            'payment_deadline' => "2022-03-29",
            'product_price' => $this->faker->numberBetween(500000, 1000000),
            'shipping_cost' => $this->faker->numberBetween(30000, 100000),
            'grand_total' => $this->faker->numberBetween(2000000, 5000000),
        ];
    }
}
