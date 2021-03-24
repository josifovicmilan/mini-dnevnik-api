<?php

namespace Tests\Feature;

use Tests\TestCase;
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
    public function recieve_student_with_all_its_marks()
    {
        $classroom = Classroom::factory()->create();
        $student = Student::factory()->create(['classroom_id' => $classroom->id]);
        

        $this->getJson("/api/students/{$student->id}/marks")->assertJsonMissingExact([
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

        $student->gradeMany($marks);

        
        $this->getJson("/api/students/{$student->id}/marks")
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
        $this->assertCount(3, $student->marks);
    }
}
