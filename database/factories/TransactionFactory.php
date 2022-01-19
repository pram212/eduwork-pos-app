<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $firstUser = \App\Models\User::all()->first()->id;
        $lastUser = \App\Models\User::all()->last()->id;
        $firstType = \App\Models\Type::all()->first()->id;
        $lastType = \App\Models\Type::all()->last()->id;

        return [
            'date' => $this->faker->dateTimeBetween('-30days', 'now'),
            'voucher' => $this->faker->unique()->ean13(),
            'type_id' => $this->faker->numberBetween($firstType, $lastType),
            'user_id' => $this->faker->numberBetween($firstUser, $lastUser),
        ];
    }
}
