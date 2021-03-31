<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\PersonalData;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonalDataFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersonalData::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'school_number' => '1324567',
            'registration_number' => '123',
            'date_of_birth' => Carbon::now('-20 years'),
            'city_of_birth' => 'Krusevac',
            'borough_of_birth' => 'Krusevac',
            'country_of_birth' => 'Srbija',
            'signed_in_at' => 'prvi',
            'signed_in_as' => 'redovan',
        ];
    }
}
