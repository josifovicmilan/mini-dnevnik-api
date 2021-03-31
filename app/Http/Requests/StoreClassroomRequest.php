<?php

namespace App\Http\Requests;
use Illuminate\Validation\Factory as ValidationFactory;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreClassroomRequest extends FormRequest
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
            'classroom_number' => 'required|numeric|lte:11|gte:1',
            'year_started' => ['required',
                                Rule::unique('classrooms')->where(function($query){
                                        return $query->where('classroom_number', $this->classroom_number)
                                            ->where('year_started', $this->year_started);
                                })
                            ],
            'type' => 'required',
        ];
    }
}
