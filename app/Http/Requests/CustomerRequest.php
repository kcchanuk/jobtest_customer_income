<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            'name' => 'bail|required|string|max:191',
            'dob' => 'nullable|date_format:Y-m-d',

            // Assume profile pic file must be under 2MB
            'profile_pic' => 'nullable|file|image|max:2048'
        ];

        switch ($this->method()) {
            case 'POST':
                $rules['email'] = 'bail|required|max:191|email|unique:customers,email';

                // UTR must be a 10-digit string
                $rules['utr'] = ['bail', 'required', 'string', 'max:191', 'regex:/^[0-9]{10}$/',
                    'unique:customers,utr'];

                // Assume to accept any format for phone.
                // Usually phone country code, area code / phone number should be separate numeric input fields.
                $rules['phone'] = 'nullable|string|max:191|unique:customers,phone';
                break;

            case 'PUT':
                $rules['email'] = 'bail|required|max:191|email|unique:customers,email,' . $this->customer->id;
                $rules['utr'] = ['bail', 'required', 'string', 'max:191', 'regex:/^[0-9]{10}$/',
                    'unique:customers,utr,' . $this->customer->id];
                $rules['phone'] = 'nullable|string|max:191|unique:customers,phone,' . $this->customer->id;
        }

        return $rules;
    }
}
