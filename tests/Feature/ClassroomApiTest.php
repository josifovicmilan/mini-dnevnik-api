<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClassroomApiTest extends TestCase
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
    public function only_authenticated_user_can_create_classroom(){
        //$this->withoutExceptionHandling();
        
        $classroom = Classroom::factory()->raw();

        $response = $this->postJson('/api/classrooms', $classroom);

        $response->assertStatus(401);

    }
    /**
     *@test
     */
    public function new_classroom_with_all_required_fields_can_be_created(){

    
        $this->actingAs($this->user);
        $response = $this->postJson('/api/classrooms',[
            'classroom_number' => 10,
            'year_started' => 2020,
            'duration' => 4,
            'type' => 'природно-математички смер'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('classrooms', ['classroom_number'=> 10, 'year_started' => 2020]);
    }
    /**
     *@test
     */
    public function fail_to_create_classroom_if_classroom_number_is_not_defined(){
        $this->actingAs($this->user);
        $response = $this->postJson('/api/classrooms',[
            'year_started' => 2020,
            'duration' => 4,
            'type' => 'природно-математички смер'
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'errors' => [
                'classroom_number'
            ]
        ]);

        $classroom = Classroom::where(['year_started' => 2020, 'duration'=> 4])->first();

        $this->assertNull($classroom);
    }

      /**
    *@test
    */
    public function fail_to_create_classroom_missing_year_started(){
        $this->actingAs($this->user);
        $classroom = Classroom::factory()->raw(['year_started' => '']);
        $response = $this->postJson("/api/classrooms/", $classroom);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'errors' => [
                'year_started'
            ]
        ]);
        
        $classrooms = Classroom::first();
  

        $this->assertNull($classrooms);
    }

   
    /**
    *@test
    */
    public function fail_to_create_classroom_with_classroom_number_twelwe(){
        $this->actingAs($this->user);
        $classroom = Classroom::factory()->raw(['classroom_number' => 12, 'user_id' => $this->user->id]);
        $response = $this->postJson("/api/classrooms/", $classroom);

        $response->assertStatus(422);

        $classrooms = Classroom::first();
        $this->assertNull($classrooms);
    }

    /**
    *@test
    */
    public function fail_to_create_classroom_if_classroom_number_and_year_started_exist(){
        $this->actingAs($this->user);
        $classroom = Classroom::factory()->create(['classroom_number'=> 10, 'year_started' => 2020, 'user_id' => $this->user->id]);

        $response = $this->postJson('/api/classrooms',[
            'classroom_number'=> 10,
            'year_started' => 2020,
            'type' => 'pm'
        ]);

        $response->assertStatus(422);
    }

   
    /**
    *@test
    */
    public function user_can_view_only_his_classrooms(){
        $this->actingAs($this->user);
        $user = User::factory()->create();
        $classroom1 = Classroom::factory()->create(['classroom_number' => 10,'user_id' => $user->id]);
        $classroom2 = Classroom::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/api/classrooms');
  
        $this->assertEquals(1, $response->decodeResponseJson()->count());
    }


    /**
    *@test
    */
    public function classroom_cannot_be_viewed_by_any_user(){
        
        $user = User::factory()->create();
        $classroom = Classroom::factory()->create(['classroom_number' => 10,'user_id' => $user->id]);
        
        $this->actingAs($this->user);

        $response = $this->getJson("/api/classrooms/{$classroom->id}");

        $response->assertStatus(403);
    }


    /**
    *@test
    */
    public function update_classroom_with_valid_data(){
        $this->withoutExceptionHandling();
        $this->actingAs($this->user);
        $classroom = Classroom::factory()->create(['user_id' => $this->user->id]);
        $response =$this->putJson("/api/classrooms/{$classroom->id}",[
            'classroom_number' => 8,
            'year_started' => 2019
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('classrooms', ['classroom_number'=>8, 'year_started'=> 2019, 'duration'=> 4]);
    }

    /**
    *@test
    */
    public function fail_to_update_classroom_if_classroom_number_and_year_started_already_exists(){
        //$this->withoutExceptionHandling();
        $this->actingAs($this->user);
        $classroom1 = Classroom::factory()->create(['user_id' => $this->user->id]); //2020,7
        $classroom2 = Classroom::factory()->create(['classroom_number' => 8, 'user_id' => $this->user->id]); //2020,7

        $response = $this->putJson("/api/classrooms/{$classroom2->id}",[
            'classroom_number' => 7,
            'year_started' => 2020
        ]);

        $response->assertStatus(422);
    }

    /**
    *@test
    */
    public function fail_to_update_classroom_if_classroom_does_not_exist(){
        $this->actingAs($this->user);
        $response = $this->putJson("/api/classrooms/1",[
            'classroom_number' => 2
        ]);

        $response->assertStatus(404);
    }
  


  

    /**
    *@test
    */
    public function unauthorized_user_cannot_view_classrooms(){
        $newUser = User::factory()->create();
        $classroom1 = Classroom::factory()->create(['classroom_number' => 10,'user_id' => $newUser->id]);
        $classroom2 = Classroom::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/api/classrooms');

        $response->assertStatus(401);
    }

    /**
    *@test
    */
    public function fail_to_delete_classroom_if_not_authorized(){
    
        $classroom = Classroom::factory()->create(['user_id' => $this->user->id]);

        $this->deleteJson("/api/classrooms/{$classroom->id}")->assertStatus(401);


        $this->assertDatabaseHas('classrooms', ['id' => $classroom->id]);

    }


    /**
    *@test
    */
    public function fail_to_delete_classroom_if_not_belong_to_user(){
    
        $classroom = Classroom::factory()->create(['user_id' => $this->user->id]);

        $user = User::factory()->create();
        $this->actingAs($user);
        $this->deleteJson("/api/classrooms/{$classroom->id}")->assertStatus(403);
    }

    /**
     * @test
     */
    public function authorized_user_can_delete_classroom_that_belongs_to_him(){
    
        $classroom = Classroom::factory()->create(['user_id' => $this->user->id]);
        $this->actingAs($this->user);

        $this->deleteJson("/api/classrooms/{$classroom->id}");

        $this->assertDatabaseMissing('classrooms', ['id' => $classroom->id]);
    }

    /**
    *@test
    */
    public function deleting_classroom_deletes_sets_null_to_associated_students(){
    
        $classroom = Classroom::factory()->create(['user_id' => $this->user->id]);
        $student = Student::factory()->create(['classroom_id' => $classroom->id]);
        $this->actingAs($this->user);

        $this->deleteJson("/api/classrooms/{$classroom->id}");

        $this->assertDatabaseMissing('classrooms', ['id' => $classroom->id]);
        $this->assertDatabaseHas('students', ['id' => $student->id, 'classroom_id' => null]);
    }
}
