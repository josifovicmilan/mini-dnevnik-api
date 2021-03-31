<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\PersonalData;
use App\Models\Position;
use App\Models\PrimarySchoolData;
use App\Models\Subject;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentTest extends TestCase
{
    use RefreshDatabase;


    protected $classroom;
    protected $student;

    protected function setUp() : void {

        parent::setUp();
        $this->user = User::factory()->create();
        $this->classroom = Classroom::factory()->create(['user_id' => $this->user->id]);
        $this->student = Student::factory()->create();
        
    }

    /**
    *@test
    */
    public function new_student_can_be_created_for_a_classroom(){
      
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $classroom = Classroom::factory()->create(['user_id' => $user->id]);

        $student= $classroom->addStudent([
            'first_name' => 'Pera',
            'last_name' => 'Peric',
            'fathers_name' => 'Milorad',
            'jmbg' => '1234567980123',
        ]);

        $this->assertCount(1, $classroom->students);
        $this->assertDatabaseHas('students', ['first_name' => 'Pera', 'classroom_id' => $classroom->id]);
   
    }       

    /**
    *@test
    */
    public function personal_data_can_be_creted_for_a_student(){
        $personal_data = PersonalData::factory()->raw(['student_id' => $this->student->id]);

        $this->student->savePersonalData($personal_data);

        $this->assertEquals(1, $this->student->personalData->count());
    }

    /**
    *@test
    */
    public function personal_data_can_be_updated_for_a_student(){
    
        $personal_data = PersonalData::factory()->create(['student_id' => $this->student->id]);
        $new_data = PersonalData::factory()->raw(['city_of_birth'=> 'Beograd']);

        $this->student->updatePersonalData($new_data);

        $this->assertEquals('Beograd', $this->student->fresh()->personalData->city_of_birth);
    }

    /**
    *@test
    */
    public function primary_school_data_can_be_created_for_a_student(){

        $primary_school = PrimarySchoolData::factory()->raw();
        $this->student->savePrimarySchoolData($primary_school);
       

        $this->assertEquals(1, $this->student->primarySchool->count());
    }
   

     /**
    *@test
    */
    public function when_student_is_created_it_receives_a_position_in_classroom(){
    
        $this->withoutExceptionHandling();
        $student = Student::factory()->create(['classroom_id' => $this->classroom->id]);


        $this->assertDatabaseHas('positions', ['positionable_type' => Student::class, 'positionable_id' => $student->id, 'classroom_id' => $this->classroom->id]);
        //$this->assertCount(1, $student->positions);
    }


    /**
    *@test
    */
    public function mark_can_be_updated_for_a_student(){
    
        $subject = Subject::factory()->create();

        $this->student->grade($subject->id, 1, 'I');

        $this->student->updateGrade($subject->id, 2, 'I');

        $this->assertDatabaseHas('marks', ['subject_id' => $subject->id, 'mark' => 2, 'degree' => 'I']);
        $this->assertDatabaseMissing('marks', ['subject_id' => $subject->id, 'mark' => 1, 'degree' => 'I']);
    }
}
