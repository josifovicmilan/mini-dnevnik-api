<?php

namespace Database\Factories;

use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => 'Pera',
            'last_name' => 'Peric',
            'jmbg' => '1234567890123',
            'fathers_name' => 'Milutin',
            'classroom_id' => Classroom::inRandomOrder()->first()->id,
        ];
    }
}
