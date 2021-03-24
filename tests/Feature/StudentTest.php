<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Classroom;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentTest extends TestCase
{
    use RefreshDatabase;
    /**
    *@test
    */
    public function new_student_can_be_created(){
      
        $classroom = Classroom::factory()->create();

        $student= $classroom->students()->create([
            'first_name' => 'Pera',
            'last_name' => 'Peric',
            'fathers_name' => 'Milorad',
            'jmbg' => '1234567980123',
        ]);


        $this->assertDatabaseHas('students', ['first_name' => 'Pera', 'classroom_id' => $classroom->id]);
        $this->assertEquals('Pera', $student->first_name);
    }       
}
