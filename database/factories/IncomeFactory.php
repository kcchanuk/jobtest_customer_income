<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\TaxYear;
use Illuminate\Database\Eloquent\Factories\Factory;

class IncomeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Assume tax year is 2021/2022
        $tax_year = TaxYear::firstWhere('start_year', '2021');

        return [
            'description' => $this->faker->sentence(),
            'amount' => $this->faker->randomNumber(5, false),

            // Assume income date is within 2021/2022 tax year
            'income_date' => $this->faker->dateTimeBetween('2021-04-06', '2022-04-05')->format('Y-m-d'),

            'customer_id' => Customer::factory(),
            'tax_year_id' => $tax_year->id
        ];
    }
}
