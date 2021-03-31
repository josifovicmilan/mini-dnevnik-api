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
    public function user_can_view_subjects_attached_to_his_classroom(){
        $this->withoutExceptionHandling();
        $this->actingAs($this->user);
        $subject = Subject::factory()->create();
        $this->classroom->addSubject($subject);
        $response = $this->getJson("/api/classrooms/{$this->classroom->id}/subjects");


        $response->assertStatus(200)
        ->assertJsonStructure([
          'data' => [
            '*' =>[
              'name',
              'type',
            ]
          ]
        ]);
    }
}
