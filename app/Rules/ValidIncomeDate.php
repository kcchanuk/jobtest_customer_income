<?php

namespace App\Rules;

use App\Models\TaxYear;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Carbon;

class ValidIncomeDate implements Rule
{
    protected $tax_year_id;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($tax_year_id)
    {
        $this->tax_year_id = $tax_year_id;
    }

    /**
     * Determine if the validation rule passes.
     * Ensure the income date is within the tax year.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // $value is the income date
        $tax_year = TaxYear::find($this->tax_year_id);

        $tax_year_start_date_carbon = Carbon::createFromFormat('Y-m-d', $tax_year->start_date);
        $tax_year_end_date_carbon = Carbon::createFromFormat('Y-m-d', $tax_year->end_date);

        $income_date_carbon = Carbon::createFromFormat('Y-m-d', $value);

        return $income_date_carbon->isBetween($tax_year_start_date_carbon, $tax_year_end_date_carbon);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Income date is not in the selected tax year.';
    }
}
