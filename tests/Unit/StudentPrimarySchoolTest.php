<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Classroom;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentPrimarySchoolTest extends TestCase
{
    use RefreshDatabase;


    protected $classroom;
    protected $student;

    protected function setUp() : void {

        parent::setUp();
        $this->classroom = Classroom::factory()->create();
        $this->student = Student::factory()->create(['classroom_id' => $this->classroom->id]);
        
    }

    /**
    *@test
    */
    public function create_primary_school_information_for_student(){
        $language_subject = Subject::factory()->create(['name'=> 'ita']);
        $chosen_subject = Subject::factory()->create(['name'=> 'versko']);
        $packet_subject1 = Subject::factory()->create(['name'=> 'jmk']);
        $packet_subject2 = Subject::factory()->create(['name'=> 'pgd']);
        $this->student->primarySchool()->create([
            'primary_school_name' => 'Vuk Karadzic',
            'gender' => 'м',
            'language_subject' => $language_subject->id,
            'chosen_subject' => $chosen_subject->id,
            'packet_subject1' => $packet_subject1->id,
            'packet_subject2' => $packet_subject2->id,
            'points' => '87.21'
        ]);


        $this->assertDatabaseHas('primary_school_data',['primary_school_name' => 'Vuk Karadzic', 'chosen_subject' => $chosen_subject->id]);
    }
}
