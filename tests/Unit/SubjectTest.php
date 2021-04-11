<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\Position;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubjectTest extends TestCase
{

    use RefreshDatabase;

    protected $user;
    protected $classroom;

    protected function setUp() : void{
        parent::setUp();

        $this->user = User::factory()->create();
        $this->classroom = Classroom::factory()->create(['user_id' => $this->user->id]);

    }
     /**
    *@test
    */
    // public function subject_has_next_subject(){
    //     $subject1 = Subject::factory()->create(['name' => 'математика','position' => 1]);
    //     $subject2 = Subject::factory()->create(['name' => "физика" , 'position' => 2]);

    //     $this->assertEquals($subject2->name, $subject1->next()->name);
    //     $this->assertNull($subject2->next());
    // }

    // /**
    // *@test
    // */
    // public function subject_has_previous_subject(){
    //     $subject1 = Subject::factory()->create(['name' => 'математика','position' => 1]);
    //     $subject2 = Subject::factory()->create(['name' => "физика" , 'position' => 2]);

    //     $this->assertEquals($subject1->name, $subject2->prev()->name);
    //     $this->assertNull($subject1->prev());
    // }

    // /**
    // *@test
    // */
    // public function two_subjects_can_exchange_position(){
    //     $subject1 = Subject::factory()->create(['name' => 'математика','position' => 1]);
    //     $subject2 = Subject::factory()->create(['name' => "физика" , 'position' => 2]);

    //     Subject::swap($subject1, $subject2);

    //     $this->assertEquals(1, $subject2->position);
    //     $this->assertEquals(2, $subject1->position);
    // }

    //   /**
    // *@test
    // */
    // public function when_subject_is_deleted_all_student_marks_from_that_subject_are_deleted(){
    
    //     $user = User::factory()->create();
    //     $classroom = Classroom::factory()->create(['user_id' => $user->id]);
    //     $student = Student::factory()->create();

    //     $subject = Subject::factory()->create();

    //     $student->grade($subject->id, 4, 'I');
    //     $student->grade($subject->id, 4, 'II');

    //     $subject->delete();

    //     $this->assertCount(0, $student->fresh()->marks);

    // }

    

    /**
    *@test
    */
    public function subject_can_have_position_in_a_classroom(){
    
        
        $subject = Subject::factory()->create();

        $subject->addPosition($this->classroom);
        
        $this->assertCount(1, $subject->positions()->get());
        
    }



}
