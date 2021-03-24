<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClassroomStudentApiTest extends TestCase
{
    use RefreshDatabase;
    /**
    *@test
    */
    public function new_student_with_valid_data_can_be_created(){
        //$this->withoutExceptionHandling();
        $classroom = Classroom::factory()->create();

        $response = $this->postJson("/api/classrooms/{$classroom->id}/students",[
            'first_name' => 'Pera',
            'last_name' => 'Peric',
            'jmbg' => '1234567890123',
            'fathers_name' => 'Milutin'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('students', ['first_name' => 'Pera', 'last_name' => 'Peric']);
    
    }

    /**
    *@test
    */
    public function fail_to_create_a_student_if_jmbg_already_exists(){
        $classroom = Classroom::factory()->create();

        $student = Student::factory()->create(['jmbg' => '1234567890123', 'classroom_id' => $classroom->id]);

        $response = $this->postJson("/api/classrooms/{$classroom->id}/students",[
            'first_name' => 'Mika',
            'last_name' => 'Mikic',
            'jmbg' => '1234567890123',
            'fathers_name' => 'Milutin'
        ]);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('students', ['first_name' => 'Mika', 'last_name' => 'Mikic']);
    
    }

    /**
    *@test
    */
    public function fail_to_create_student_with_invalid_jmbg(){
        $classroom = Classroom::factory()->create();

        $response = $this->postJson("/api/classrooms/{$classroom->id}/students",[
            'first_name' => 'Mika',
            'last_name' => 'Mikic',
            'jmbg' => '1234567',
            'fathers_name' => 'Milutin'
        ]);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('students', ['first_name' => 'Mika', 'last_name' => 'Mikic']);
    }
    
    /**
    *@test
    */
    public function fail_to_update_student_if_invalid_jmbg_given(){
        //$this->withoutExceptionHandling();
        $classroom = Classroom::factory()->create();

        $student = Student::factory()->create(['classroom_id' => $classroom->id]);

        $response = $this->putJson("/api/classrooms/{$classroom->id}/students/{$student->id}",[
            'jmbg' => '123',
            'first_name' => 'Pera',
            'last_name' => 'Peric',
            'fathers_name' => 'Milutin'
        ]);

        $response->assertStatus(422);

        $this->assertDatabaseHas('students', ['first_name' => 'Pera', 'jmbg' => '1234567890123']);
    
    }

    /**
    *@test
    */
    public function fail_to_update_student_if_updated_jmbg_already_exists(){
        $classroom = Classroom::factory()->create();

        $student1 = Student::factory()
                    ->create(['classroom_id' => $classroom->id, 'jmbg' => '1234567890123']);
        $student2 = Student::factory()
                    ->create(['classroom_id' => $classroom->id, 'jmbg' => '7894561230123']);

        $response = $this->putJson("/api/classrooms/{$classroom->id}/students/{$student2->id}",[
            'jmbg' => '1234567890123',
            'first_name' => 'Pera',
            'last_name' => 'Peric',
            'fathers_name' => 'Milutin'
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseHas('students', ['first_name' => 'Pera', 'jmbg' => '7894561230123']);
    }
}
