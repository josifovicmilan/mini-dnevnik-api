<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Classroom;
use Illuminate\Foundation\Testing\WithFaker;
use App\Exceptions\StudentAlreadyGradedException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentSubjectTest extends TestCase
{

    use RefreshDatabase;
    protected $classroom;
    protected $student;
    protected function setUp():void{
        parent::setUp();
        $this->classroom = Classroom::factory()->create();
        $this->student = Student::factory()->create(['classroom_id' => $this->classroom->id]);
    }
    /**
    *@test
    */
    public function student_can_be_graded_from_a_subject(){
        
        $subject = Subject::factory()->create();

        $this->student->grade($subject->id, 5, 'I');

        $this->assertDatabaseHas('marks',['mark' => 5, 'subject_id' => $subject->id, 'student_id'=> $this->student->id]);

    }

    /**
    *@test
    */
    public function student_can_be_graded_from_many_subjects(){
       
        $subjects = Subject::factory()->count(3)->create();
        $marks = [];
        foreach( $subjects as $key => $subject){
          array_push($marks, (object)[
            'subject_id' => $subject->id,
            'mark' => mt_rand(1,5),
            'degree' => 'I'
          ]);
        }

        $this->student->gradeMany($marks);

        $this->assertEquals(3, $this->student->marks->count());
    }

    /**
    *@test
    */
    public function student_cannot_be_graded_from_a_subject_in_a_degree_where_it_already_has_a_mark(){
        //$this->withoutExceptionHandling();
        $subject = Subject::factory()->create();

        $this->student->grade($subject->id, 5, 'I');

        $this->assertDatabaseHas('marks', ['mark' => 5, 'subject_id' => $subject->id, 'student_id'=> $this->student->id]);

        try{
            $this->student->grade($subject->id, 5, 'I');
        }
        catch(StudentAlreadyGradedException $e){
            return;
        }
        $this->expectException(StudentAlreadyGradedException::class);

        $this->fail("Succed in creating a mark although mark exists");
    }
}
