<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp() : void {

        parent::setUp();
    
        $this->user = User::factory()->create();
    }
    /**
    *@test
    */
    public function unauthorized_user_cannot_view_subjects(){
        $response = $this->getJson('api/subjects')->assertStatus(401);
    }

    /**
    *@test
    */
    public function unauthorized_user_cannot_attach_subjects(){
    
        $subject = Subject::factory()->create();

        $this->postJson("/api/users/{$this->user->id}/subjects", $subject->toArray())->assertStatus(401);


    }

    // /**
    // *@test
    // */
    // public function user_can_view_subjects_that_are_used_in_his_classrooms(){
    //     $this->actingAs($this->user);
        
    //     $subjects = Subject::factory()->count(5)->create();        
    //     $subject = Subject::first();
    //     $this->user->classrooms()->addSubject($subject);

    //     $this->assertDatabaseHas('positions', ['user_id' => $this->user->id, 'subject_id' => $subject->id]);

    //     $response = $this->getJson("/api/users/{$this->user->id}/subjects");

    //     $response->assertJsonStructure([
    //         "data" => [
    //             '*' => [
    //                 'name',
    //                 'type',
    //                 'position'
    //             ]
    //         ]
    //     ]);
    // }

    // /**
    // *@test
    // */
    // public function user_can_attach_subjects_to_use(){
    
    //     $this->actingAs($this->user);

    //     $subject = Subject::factory()->create();

    //     $response = $this->postJson("/api/users/{$this->user->id}/subjects",[
    //         'subject_id' => $subject->id
    //     ]);

    //     $response->assertStatus(200);
    //     $this->assertDatabaseHas('positions',['user_id' => $this->user->id, 'subject_id'=> $subject->id]);

    // }

    // /**
    // *@test
    // */
    // public function user_can_update_position_for_a_subject(){
    //     $this->actingAs($this->user);

    //     $subject = Subject::factory()->create();

    //     $this->user->attachSubject($subject);

    //     $response = $this->patchJson("/api/users/{$this->user->id}/subjects/{$subject->id}",[
    //         'position' => 1
    //     ]);

    //     $response->assertStatus(201);

    //     $this->assertDatabaseHas('positions',['user_id' => $this->user->id, 'subject_id' => $subject->id, 'position' =>1]);
    // }
}
