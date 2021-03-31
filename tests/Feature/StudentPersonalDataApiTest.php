<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\PersonalData;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentPersonalDataApiTest extends TestCase
{
    use RefreshDatabase;


    protected $classroom;
    protected $student;
    protected $user;

    protected function setUp() : void {

        parent::setUp();
        $this->user = User::factory()->create();
        $this->classroom = Classroom::factory()->create(['user_id' => $this->user->id]);
        $this->student = Student::factory()->create(['classroom_id' => $this->classroom->id]);
        
    }

    /**
    *@test
    */
    public function fail_to_create_personal_data_for_a_student_if_user_is_not_authorized(){
        $personal_data = PersonalData::factory()->raw();

        $response = $this->postJson("/api/students/{$this->student->id}/personal-data", $personal_data);

        $response->assertStatus(401);

        $this->assertDatabaseMissing('personal_data', $personal_data);
    }

    /**
    *@test
    */
    public function fail_to_create_personal_data_for_student_if_student_doesnt_belong_to_user(){
        $this->actingAs($this->user);


        $user = User::factory()->create();
        $classroom = Classroom::factory()->create(['user_id'=> $user->id]);
        $student = Student::factory()->create(['classroom_id' => $classroom->id]);
        $personal_data = PersonalData::factory()->raw();

        $response = $this->postJson("/api/students/{$student->id}/personal-data", $personal_data);

        $response->assertStatus(403);

        $this->assertDatabaseMissing('personal_data', $personal_data);
    }
    /**
    *@test
    */
    public function display_personal_data_for_a_student_if_student_belongs_to_user(){
        $this->actingAs($this->user);

        $personalData = PersonalData::factory()->create(['student_id' => $this->student->id]);
        $this->getJson("/api/students/{$this->student->id}/personal-data")
            ->assertJsonStructure([
                'registration_number',
                'school_number',
                'city_of_birth'
            ]);
    }
    /**
    *@test
    */
    public function new_valid_personal_data_can_be_added_to_existing_student(){
        $this->actingAs($this->user);
        $personalData = PersonalData::factory()->raw();
        $response = $this->postJson("/api/students/{$this->student->id}/personal-data",$personalData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('personal_data', ['school_number' => $this->student->personalData->school_number]);
        
    }

    /**
    *@test
    */
    public function fail_to_create_personal_data_if_any_of_data_is_missing(){
        $this->actingAs($this->user);
        $personalData = PersonalData::factory()->raw(['city_of_birth' => '']);
        $response = $this->postJson("/api/students/{$this->student->id}/personal-data",$personalData);

        $response->assertStatus(422);

        $this->assertNull($this->student->personalData);
    }

    /**
    *@test
    */
    public function update_personal_data_for_a_student(){
        $this->withoutExceptionHandling();
        $this->actingAs($this->user);

        $personalData = PersonalData::factory()->create(['student_id' => $this->student->id]);
        $response = $this->putJson("/api/students/{$this->student->id}/personal-data/update",[
        
            'registration_number' => '123',
            'date_of_birth' => Carbon::now('-20 years'),
            'city_of_birth' => 'Krusevac',
            'borough_of_birth' => 'Krusevac',
            'country_of_birth' => 'Srbija',
            'signed_in_at' => 'prvi',
            'signed_in_as' => 'redovan',
        ]);
        
        $response->assertStatus(201);

        $this->assertDatabaseHas('personal_data', ['registration_number' => '123']);
    }
}
