<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClassroomTest extends TestCase
{
    use RefreshDatabase;

   /**
    * @test
    */
   public function new_classroom_can_be_created(){

       $classroom = Classroom::factory()
                    ->create(['classroom_number' => 1, 'year_started' => 2020]);
        
        $this->assertDatabaseHas('classrooms', ['classroom_number' => $classroom->classroom_number, 'year_started' => 2020]);
   }
   
   /**
   *@test
   */
   public function classroom_has_active_years_attribute(){
        $classroom = Classroom::factory()
                    ->create(['year_started' => 2020]);

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
        $classroom = Classroom::factory()->create(['year_started'=>2020, 'classroom_number' => 10]);
        $student = Student::factory()->create(['classroom_id' => $classroom->id]);

        $classroom->delete();

        $this->assertNull($student->fresh()->classroom_id);
        $this->assertDatabaseMissing('classrooms', ['year_started' => 2020, 'classroom_number' => 10]);

    }

    
}
