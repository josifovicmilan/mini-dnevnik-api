<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePrimarySchoolRequest extends FormRequest
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
            'primary_school_name' => 'required',
            'gender' => 'required|in:м,ж',
            'language_subject' => 'required',
            'chosen_subject' => 'required',
            'packet_subject1' => 'required',
            'packet_subject2' => 'required',
            'points' => 'required|numeric'
        ];
    }
}
