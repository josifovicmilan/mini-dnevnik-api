<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class StudentPersonalDataApiTest extends TestCase
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
    *@test
    */
    public function display_personal_data_for_a_student(){
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
        $this->getJson("/api/students/{$this->student->id}/personal-data")->assertJsonStructure([
            'registration_number',
            'school_number',
            'city_of_birth'
        ]);
    }
    /**
    *@test
    */
    public function new_valid_personal_data_can_be_added_to_existing_student(){
        
        $response = $this->postJson("/api/students/{$this->student->id}/personal-data",[
            'school_number' => '1324567',
            'registration_number' => '123',
            'date_of_birth' => Carbon::now('-20 years'),
            'city_of_birth' => 'Krusevac',
            'borough_of_birth' => 'Krusevac',
            'country_of_birth' => 'Srbija',
            'signed_in_at' => 'prvi',
            'signed_in_as' => 'redovan',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('personal_data', ['school_number' => $this->student->personalData->school_number]);
        
    }

    /**
    *@test
    */
    public function fail_to_create_personal_data_if_any_of_data_is_missing(){
        $response = $this->postJson("/api/students/{$this->student->id}/personal-data",[
        
            'registration_number' => '123',
            'date_of_birth' => Carbon::now('-20 years'),
            'city_of_birth' => 'Krusevac',
            'borough_of_birth' => 'Krusevac',
            'country_of_birth' => 'Srbija',
            'signed_in_at' => 'prvi',
            'signed_in_as' => 'redovan',
        ]);

        $response->assertStatus(422);

        $this->assertNull($this->student->personalData);
    }

    /**
    *@test
    */
    public function update_personal_data_for_a_student(){
    
        
    }
}
