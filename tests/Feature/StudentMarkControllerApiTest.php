<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Mark;
use App\Models\User;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Classroom;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentMarkControllerApiTest extends TestCase
{

    /**
     * @test 
    */
    use RefreshDatabase;


    protected $user;
    protected $classroom;
    protected $student;
    protected function setUp() : void {

      parent::setUp();
      $this->user = User::factory()->create();
      $this->classroom = Classroom::factory()->create(['user_id' => $this->user->id]);
      $this->student = Student::factory()->create(['classroom_id' => $this->classroom->id]);
  }
    /**
    *@test
    */
    public function cannot_view_marks_if_not_authorized(){
    
      $this->getJson("/api/students/{$this->student->id}/marks")->assertStatus(401);

    }
    /**
    *@test
    */
    public function cannot_view_marks_of_a_student_that_does_not_belong_to_a_user(){
    
      $user = User::factory()->create();
      $subject = Subject::factory()->create();

      $this->student->grade($subject->id, 4, 'I');

      $this->actingAs($user);

      $response = $this->getJson("/api/students/{$this->student->id}/marks")->assertStatus(403);
    }


    /**
     * @test 
    */
    public function recieve_student_with_all_its_marks()
    {
       
        $this->actingAs($this->user);

        $this->getJson("/api/students/{$this->student->id}/marks")->assertJsonMissingExact([
          'data' => [
            'name',
            'mark',
            'descriptive_mark',
            'type',
            'degree'
        ]
        ]);
        

        $subjects = Subject::factory()->count(3)->create(['type' => 'обавезни']);
        $marks = [];
        foreach( $subjects as $key => $subject){
          array_push($marks, (object)[
            'subject_id' => $subject->id,
            'mark' => "4",
            'degree' => 'I'
          ]);
        }

        $this->student->gradeMany($marks);

        
        $this->getJson("/api/students/{$this->student->id}/marks")
              ->assertStatus(200)
              ->assertJsonStructure([
                'data' => [
                  '*' =>[
                    'name',
                    'type',
                    'mark',
                    'degree'
                  ]
                ]
              ]);
        $this->assertCount(3, $this->student->marks);
    }

    /**
    *@test
    */
    public function cannot_create_marks_for_a_student_if_not_authorized(){
      $subject = Subject::factory()->create();

      $response = $this->postJson("/api/students/{$this->student->id}/marks",[
          'subject_id' => $subject->id,
          'mark' => 4,
          'degree' => 'II'
        ]);

      $response->assertStatus(401);
    }
    
    /**
    *@test
    */
    public function cannot_grade_student_that_does_not_belong_to_user(){
    
      $subject = Subject::factory()->create();

      $user = User::factory()->create();

      $this->actingAs($user);
      
      $response = $this->postJson("/api/students/{$this->student->id}/marks",[
        'subject_id' => $subject->id,
        'mark' => 4,
        'degree' => 'II'
      ]);

      $response->assertStatus(403);
    }

    /**
    *@test
    */
    public function user_can_create_grade_for_student(){
      $this->actingAs($this->user);

      $subject = Subject::factory()->create();

      $response = $this->postJson("/api/students/{$this->student->id}/marks",[
        'subject_id' => $subject->id,
        'mark' => 4,
        'degree' => 'II'
      ]);

      $response->assertStatus(200);

      $this->assertDatabaseHas('marks', [
        'subject_id' => $subject->id,
        'mark' => 4,
        'degree' => 'II'
      ]);
    }

    /**
    *@test
    */
    public function user_can_create_many_grades_for_a_student(){
      $this->actingAs($this->user);
      $this->withoutExceptionHandling();

      $subjects = Subject::factory()->count(3)->create(['type' => 'обавезни']);
      $marks = [];
      foreach( $subjects as $key => $subject){
        array_push($marks, (object)[
          'degree' => 'I',
          'mark' => 4,
          'subject_id' => $subject->id,
        ]);
      }
     
      $response = $this->postJson("/api/students/{$this->student->id}/marks/store-many", $marks);

      $response->assertStatus(200);

      $this->assertCount(3, $this->student->marks);
    }

    /**
    *@test
    */
    public function unauthorized_user_cannot_update_mark(){
    
      $subject = Subject::factory()->create();

      $this->student->grade($subject->id, 3, 'I');
      
      $mark = Mark::where('subject_id', $subject->id)->where('mark', 3)->where('degree', 'I')->first();

      $response = $this->putJson("/api/students/{$this->student->id}/marks/{$mark->id}", [
        'subject_id' => $subject->id,
        'mark' => 5,
        'degree' => 'II'
      ]);

      $response->assertStatus(401);

      //$this->assertDatabaseHas('marks', ['subject_id' => $subject->id, 'mark' => 5, 'degree' => 'II']);

    }

    /**
    *@test
    */
    public function user_cannot_update_a_mark_for_a_student_that_does_not_belong_to_him(){
    
      
      $subject = Subject::factory()->create();
      $this->student->grade($subject->id, 3, 'I');
      $mark = Mark::where('subject_id', $subject->id)->where('mark', 3)->where('degree', 'I')->first();

      $user = User::factory()->create();
      $this->actingAs($user);

      $response = $this->putJson("/api/students/{$this->student->id}/marks/{$mark->id}", [
        'subject_id' => $subject->id,
        'mark' => 5,
        'degree' => 'II'
      ]);

      $response->assertStatus(403);

      $this->assertDatabaseMissing('marks', ['subject_id' => $subject->id, 'mark' => 5, 'degree' => 'II']);
    }

    /**
    *@test
    */
    public function user_can_update_the_mark_for_a_student(){
      $subject = Subject::factory()->create();
      $this->student->grade($subject->id, 3, 'I');
      $mark = Mark::where('subject_id', $subject->id)->where('mark', 3)->where('degree', 'I')->first();

     
      $this->actingAs($this->user);

      $response = $this->putJson("/api/students/{$this->student->id}/marks/{$mark->id}", [
        'subject_id' => $subject->id,
        'mark' => 5,
        'degree' => 'II'
      ]);

      $response->assertStatus(201);

      $this->assertDatabaseMissing('marks', ['subject_id' => $subject->id, 'mark' => 3, 'degree' => 'I']);

      $this->assertDatabaseHas('marks', ['subject_id' => $subject->id, 'mark' => 5, 'degree' => 'II']);
    }
}
