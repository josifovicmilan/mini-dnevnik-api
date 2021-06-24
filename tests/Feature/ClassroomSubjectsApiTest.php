<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Classroom;
use App\Models\Subject;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClassroomSubjectsApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $classroom;

    protected function setUp() : void {

        parent::setUp();
        $this->user = User::factory()->create();
        $this->classroom = Classroom::factory()->create(['user_id' => $this->user->id]);
    }
    /**
    *@test
    */
    public function unauthorized_user_cannot_view_subjects_for_any_classroom(){
    
        $this->getJson("/api/classrooms/{$this->classroom->id}/subjects")->assertStatus(401);
    }

    /**
    *@test
    */
    public function cannot_view_subjects_for_a_classroom_that_does_not_belong_to_user(){
    
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->getJson("/api/classrooms/{$this->classroom->id}/subjects")->assertStatus(403);

    }
    /**
    *@test
    */
    public function authorized_user_can_view_subjects_attached_to_his_classroom(){

        $this->withoutExceptionHandling();
        $this->actingAs($this->user);
        $subject = Subject::factory()->create();
        $this->classroom->addSubject($subject);


        $user = User::factory()->create();
        $classroom = Classroom::factory()->create(['user_id' => $user->id]);
        $classroom->addSubject($subject);
        $this->actingAs($user);
        $response = $this->getJson("/api/classrooms/{$classroom->id}/subjects");

        dd($response);
        $response->assertStatus(200)
        ->assertJsonStructure([
          'data' => [
            '*' =>[
              'name',
              'type',
              'position',
            ]
          ]
        ]);
    }

    /**
    *@test
    */
    public function authorized_user_can_attach_subject_to_his_classroom(){
    
      $this->withoutExceptionHandling();
      $this->actingAs($this->user);

      $subject = Subject::factory()->create();

      $response = $this->postJson("/api/classrooms/{$this->classroom->id}/subjects",[
        'subject' => $subject
      ]);

      $response->assertStatus(200);
      
      $this->assertCount(1, $this->classroom->subjects()->get());

      $this->assertDatabaseHas('positions', ['positionable_type' => Subject::class, 'positionable_id' => $subject->id, 'classroom_id' => $this->classroom->id]);

    }


    /**
    *@test
    */
    public function authorized_user_can_detach_subject_from_his_classroom(){
      $this->withoutExceptionHandling();
      $this->actingAs($this->user);

      $subject = Subject::factory()->create();

      $this->classroom->addSubject($subject);

      $this->assertDatabaseHas('classroom_subject', ['classroom_id' => $this->classroom->id, 'subject_id' => $subject->id]);
      $this->assertDatabaseHas('positions', ['positionable_type' => Subject::class, 'positionable_id'=> $subject->id, 'classroom_id' => $this->classroom->id]);
      $response = $this->deleteJson("/api/classrooms/{$this->classroom->id}/subjects/{$subject->id}");

      $response->assertStatus(200);

      $this->assertDatabaseMissing('positions', ['positionable_type' => Subject::class, 'positionable_id'=> $subject->id, 'classroom_id' => $this->classroom->id]);
      $this->assertDatabaseMissing('classroom_subject', ['classroom_id' => $this->classroom->id, 'subject_id' => $subject->id]);
    }

    /**
    *@test
    */
    public function subject_can_change_position(){
      $subjects = Subject::factory()->count(5)->create();
      
      $this->classroom->addSubjects($subjects);

      $subject1 = Subject::find(1);
      $subject2 = Subject::find(2);
      $subject3 = Subject::find(3);

      $subject1->updatePosition($subject3,$this->classroom->id);

      $this->assertEquals(3, $subject1->fresh()->positions()->inClassroom($this->classroom->id)->first()->position);
      $this->assertEquals(1, $subject2->positions()->inClassroom($this->classroom->id)->first()->position);
      $this->assertEquals(2, $subject3->positions()->inClassroom($this->classroom->id)->first()->position);
    
    }
    /**
     * @test
     */
    public function two_subjects_can_excange_position_api(){
      $this->withoutExceptionHandling();
      $subjects = Subject::factory()->count(5)->create();
      $this->actingAs($this->user);
      $this->classroom->addSubjects($subjects);
      $subject1 = Subject::find(1);
      $subject2 = Subject::find(2);
      $response = $this->patchJson("/api/subjects/{$subject1->id}/subjects/{$subject2->id}",[
          'classroom_id' => $this->classroom->id
      ]);
      $response->assertStatus(200);
      $this->assertEquals(1, $subject2->positions()->inClassroom($this->classroom->id)->first()->position);
    }
}
