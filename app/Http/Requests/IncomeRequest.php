<?php

namespace App\Http\Requests;

use App\Models\TaxYear;
use App\Rules\ValidIncomeDate;
use Illuminate\Foundation\Http\FormRequest;

class IncomeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Assume no authentication needed
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'description' => 'bail|required|string|max:191',
            'amount' => 'bail|required|numeric|min:1|max:999999999999.99',

            'tax_year_id' => 'bail|required|exists:tax_years,id',

            // Assume income file must be of below mime types and under 10MB
            'income_file' => 'nullable|file|mimes:jpg,jpeg,png,webp,doc,docx,xls,xlsx,pdf|max:10240'
        ];

        // Ensure income date is in the selected tax year if tax year is valid
        if (!empty($this->tax_year_id) and TaxYear::where('id', $this->tax_year_id)->exists()) {
            $rules['income_date'] = ['bail', 'required', 'date_format:Y-m-d', new ValidIncomeDate($this->tax_year_id)];
        } else {
            $rules['income_date'] = 'bail|required|date_format:Y-m-d';
        }

        return $rules;
    }
}
