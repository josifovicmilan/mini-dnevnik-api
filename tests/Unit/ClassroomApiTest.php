<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Classroom;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClassroomApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     *@test
     */
    public function new_classroom_with_all_required_fields_can_be_created(){
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
    public function error_occurs_if_classroom_number_is_not_defined(){
        $response = $this->postJson('/api/classrooms',[
            'year_started' => 2020,
            'duration' => 4,
            'type' => 'природно-математички смер'
        ]);

        $response->assertStatus(422);

        $classroom = Classroom::where(['year_started' => 2020, 'duration'=> 4])->first();

        $this->assertNull($classroom);
    }

    /**
    *@test
    */
    public function cannot_create_classroom_with_same_year_started_and_classroom_number(){
        //$this->withoutExceptionHandling();
        $classroom = Classroom::factory()->create(['classroom_number'=> 10, 'year_started' => 2020]);

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
    public function update_classroom_with_valid_data(){
        $classroom = Classroom::factory()->create();
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
        $classroom1 = Classroom::factory()->create(); //2020,7
        $classroom2 = Classroom::factory()->create(['classroom_number' => 8]); //2020,7

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
        $response = $this->putJson("/api/classrooms/1",[
            'classroom_number' => 2
        ]);

        $response->assertStatus(404);
    }

    /**
    *@test
    */
    public function fail_to_create_classroom_stared_year_with_string_data(){
        $response = $this->postJson("/api/classrooms/",[
            'classroom_number' => 7,
            'year_started' => 'dve hiljade i prva'
        ]);

        $response->assertStatus(422);

        $classrooms = Classroom::first();
        $this->assertNull($classrooms);
    }

    /**
    *@test
    */
    public function fail_to_create_classroom_with_classroom_number_twelwe(){
        $response = $this->postJson("/api/classrooms/",[
            'classroom_number' => 12,
            'year_started' => 2020
        ]);

        $response->assertStatus(422);

        $classrooms = Classroom::first();
        $this->assertNull($classrooms);
    }
}
