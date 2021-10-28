<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'utr' => $this->faker->unique()->numerify('##########'),
            'dob' => $this->faker->date(),
            'phone' => $this->faker->unique()->phoneNumber()
        ];
    }
}
