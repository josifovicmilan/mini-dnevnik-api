<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePersonalDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'school_number' => 'required|numeric',
            'registration_number' => 'required|numeric',
            'date_of_birth' => 'required|date',
            'city_of_birth' => 'required|alpha',
            'borough_of_birth' => 'required|alpha',
            'country_of_birth' => 'required|alpha',
            'signed_in_at' => 'required',
            'signed_in_as' => 'required',
        ];
    }
}
