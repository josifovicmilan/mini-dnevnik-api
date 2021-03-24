<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentPersonalTest extends TestCase
{
    use RefreshDatabase;


    protected $classroom;
    protected $student;

    protected function setUp() : void {

        parent::setUp();
        $this->classroom = Classroom::factory()->create();
        $this->student = Student::factory()->create(['classroom_id' => $this->classroom->id]);
        
    }

    /**
    * @test
    */
    public function personal_data_can_belong_to_student(){
        $this->student->personalData()->create([
            'school_number' => '1324567',
            'registration_number' => '123',
            'date_of_birth' => Carbon::now('-20 years'),
            'city_of_birth' => 'Krusevac',
            'borough_of_birth' => 'Krusevac',
            'country_of_birth' => 'Srbija',
            'signed_in_at' => 'prvi',
            'signed_in_as' => 'redovan',
        ]);
        
        $this->assertDatabaseHas('personal_data', ['school_number' => $this->student->personalData->school_number]);
    }
}
