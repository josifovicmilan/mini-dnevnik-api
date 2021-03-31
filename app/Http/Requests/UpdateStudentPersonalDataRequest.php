<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentPersonalDataRequest extends FormRequest
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
            'school_number' => 'numeric',
            'registration_number' => 'numeric',
            'date_of_birth' => 'date',
            'city_of_birth' => 'alpha',
            'borough_of_birth' => 'alpha',
            'country_of_birth' => 'alpha',
        ];
    }
}
