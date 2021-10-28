<?php

namespace Database\Seeders;

use App\Models\TaxYear;
use Illuminate\Database\Seeder;

class TaxYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Generate tax year from 2021/2022 to 2030/2031
        for ($i = 2021; $i < 2031; $i++) {
            TaxYear::create(['start_year' => $i]);
        }
    }
}
