<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\Position;
use App\Models\Subject;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClassroomTest extends TestCase
{
    use RefreshDatabase;

   /**
    * @test
    */
   public function new_classroom_can_be_created(){
    $user = User::factory()->create();

    $classroom = Classroom::factory()
                    ->create(['classroom_number' => 1, 'year_started' => 2020, 'user_id'=> $user->id]);
        
        $this->assertDatabaseHas('classrooms', ['classroom_number' => $classroom->classroom_number, 'year_started' => 2020]);
   }

   /**
   *@test
   */
   public function classroom_has_active_years_attribute(){
        $user = User::factory()->create();
        $classroom = Classroom::factory()
                    ->create(['year_started' => 2020, 'user_id' => $user->id]);

        $this->assertEquals([
            'I' => '2020-2021',
            'II' => '2021-2022',
            'III' => '2022-2023',
            'IV' => '2023-2024',
        ], $classroom->active_years);
   
    }

    /**
    *@test
    */
    public function when_classroom_is_deleted_student_classroom_id_is_set_to_null(){
        $user = User::factory()->create();
        $classroom = Classroom::factory()->create(['year_started'=>2020, 'classroom_number' => 10, 'user_id' => $user->id]);
        $student = Student::factory()->create();

        $classroom->delete();

        $this->assertNull($student->fresh()->classroom_id);
        $this->assertDatabaseMissing('classrooms', ['year_started' => 2020, 'classroom_number' => 10]);

    }

 
    /**
    *@test
    */
    public function subject_can_be_added_to_a_classroom(){
    
        $user = User::factory()->create();
        $classroom = Classroom::factory()->create(['user_id' => $user->id]);

        $subject = Subject::factory()->create();

        $classroom->addSubject($subject);

        $this->assertCount(1, $classroom->subjects);
    }


    /**
    *@test
    */
    public function subjects_can_be_removed_from_a_classroom(){
    
        $user = User::factory()->create();
        $classroom = Classroom::factory()->create(['user_id' => $user->id]);
        $subject = Subject::factory()->create();
        $classroom->addSubject($subject);
        $this->assertCount(1, $classroom->subjects);

        $classroom->removeSubject($subject);
        
        $this->assertCount(0, $classroom->fresh()->subjects);
        $this->assertDatabaseMissing('classroom_subject', ['subject_id' => $subject->id, 'classroom_id' => $classroom->id]);
        $this->assertDatabaseMissing('positions', ['positionable_type' => Subject::class, 'positionable_id' => $subject->id, 'classroom_id' => $classroom->id]);
    }
    /**
    *@test
    */
    public function adding_subject_to_classroom_adds_a_position_for_a_subject(){
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $classroom = Classroom::factory()->create(['user_id' => $user->id]);

        $subject = Subject::factory()->create();

        $classroom->addSubject($subject);

        $this->assertCount(1, $subject->positions()->get());
    }

    /**
    *@test
    */
    public function classroom_has_its_students(){
    
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $classroom = Classroom::factory()->create(['user_id' => $user->id]);

        $student = Student::factory()->create(['classroom_id' => $classroom->id]);
    

        $this->assertCount(1, $classroom->students);

    }

    /**
    *@test
    */
    public function many_subjects_can_be_added_to_classroom(){
        $user = User::factory()->create();
        $classroom = Classroom::factory()->create(['user_id' => $user->id]);

        $subjects = Subject::factory()->count(2)->create();

        $this->assertCount(2, $subjects);


        $this->assertEmpty($classroom->subjects);

        $classroom->addSubjects($subjects);

        
        $this->assertCount(2, $classroom->fresh()->subjects);
    }



}
