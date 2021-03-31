<?php

namespace App\Http\Requests;

use App\Models\Subject;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreManyMarksRequest extends FormRequest
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
        $degrees = ['I', "II", "III", "IV"];
        return [
            "*.mark" => 'required|numeric|min:1|max:5',
            "*.degree" => "required|" . Rule::in($degrees),
            '*.subject_id' => Rule::in(Subject::all()->pluck('id'))
        ];
    }

    // public function toBag(){
    
    // }
}
