<?php

namespace Database\Factories;

use App\Models\Subject;
use App\Models\PrimarySchoolData;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrimarySchoolDataFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PrimarySchoolData::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $subjects = Subject::factory()->count(5)->create();

       
        return [
            'primary_school_name' => 'Vuk Karadzic',
            'gender' => 'м',
            'language_subject' => $subjects->random(1)->first()->id,
            'chosen_subject' => $subjects->random(1)->first()->id,
            'packet_subject1' => $subjects->random(1)->first()->id,
            'packet_subject2' => $subjects->random(1)->first()->id,
            'points' => '87.21'
        ];
    }
}
