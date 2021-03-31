<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\PrimarySchoolData;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentPrimarySchoolApiTest extends TestCase
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
    public function cannot_view_primary_school_information_about_a_student_if_not_authorized_user(){
    
        $response = $this->getJson("/api/students/{$this->student->id}/primary-school-data")->assertStatus(401);
    }

    /**
    *@test
    */
    public function cannot_view_primary_school_information_about_a_student_if_student_does_not_belong_to_a_user(){
        $this->actingAs($this->user);

        $user = User::factory()->create();
        $classroom = Classroom::factory()->create(['user_id' => $user->id]);
        $student = Student::factory()->create(['classroom_id' => $classroom->id]);

        $response = $this->getJson("/api/students/{$student->id}/primary-school-data")->assertStatus(403);
    }
    /**
    *@test
    */
    public function create_new_primary_school_data_for_a_student_with_valid_data(){
        $this->actingAs($this->user);
        $language_subject = Subject::factory()->create(['name'=> 'ita']);
        $chosen_subject = Subject::factory()->create(['name'=> 'versko']);
        $packet_subject1 = Subject::factory()->create(['name'=> 'jmk']);
        $packet_subject2 = Subject::factory()->create(['name'=> 'pgd']);
        $response = $this->postJson("/api/students/{$this->student->id}/primary-school-data",[
            'primary_school_name' => 'Vuk Karadzic',
            'gender' => 'м',
            'language_subject' => $language_subject->id,
            'chosen_subject' => $chosen_subject->id,
            'packet_subject1' => $packet_subject1->id,
            'packet_subject2' => $packet_subject2->id,
            'points' => '87.21'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('primary_school_data', [
            'primary_school_name' => 'Vuk Karadzic',
            'gender' => 'м',
            'language_subject' => $language_subject->id,
            ]);

    }

    /**
    *@test
    */
    public function fail_to_create_primary_school_data_for_student_if_wront_data_passed_to_gender(){
        $this->actingAs($this->user);
        $language_subject = Subject::factory()->create(['name'=> 'ita']);
        $chosen_subject = Subject::factory()->create(['name'=> 'versko']);
        $packet_subject1 = Subject::factory()->create(['name'=> 'jmk']);
        $packet_subject2 = Subject::factory()->create(['name'=> 'pgd']);
        $response = $this->postJson("/api/students/{$this->student->id}/primary-school-data",[
            'gender' => 'm',
            'primary_school_name' => 'Vuk Karadzic',
            'language_subject' => $language_subject->id,
            'chosen_subject' => $chosen_subject->id,
            'packet_subject1' => $packet_subject1->id,
            'packet_subject2' => $packet_subject2->id,
            'points' => '87.21'
        ]);

        $response->assertStatus(422);

        $this->assertNull($this->student->primarySchool);
        
    }

     /**
    *@test
    */
    public function fail_to_create_primary_school_data_for_student_if_data_missing(){
        $this->actingAs($this->user);

        $chosen_subject = Subject::factory()->create(['name'=> 'versko']);
        $packet_subject1 = Subject::factory()->create(['name'=> 'jmk']);
        $packet_subject2 = Subject::factory()->create(['name'=> 'pgd']);
        $response = $this->postJson("/api/students/{$this->student->id}/primary-school-data",[
            'primary_school_name' => 'Vuk Karadzic',
            'gender' => 'м',

            'chosen_subject' => $chosen_subject->id,
            'packet_subject1' => $packet_subject1->id,
            'packet_subject2' => $packet_subject2->id,
            'points' => '87.21'
        ]);

        $response->assertStatus(422);
        $this->assertNull($this->student->primarySchool);
        
    }

     /**
    *@test
    */
    public function update_primary_school_data_with_valid_data(){

        $this->actingAs($this->user);

        $primary_school_data = PrimarySchoolData::factory()->create(['student_id' => $this->student->id]);
           
        $response = $this->putJson("/api/students/{$this->student->id}/primary-school-data",[
            'primary_school_name' => 'Vuk Karadzic',
            'gender' => 'м',
            'language_subject' => 1,
            'chosen_subject' => 2,
            'packet_subject1' => 3,
            'packet_subject2' => 4,
            'points' => '91.24'
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('primary_school_data',['student_id' => $this->student->id, 'points' => '91.24']);
        
    }
}
