<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Subject;

use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubjectApiTest extends TestCase
{
    use RefreshDatabase;

    /**
    *@test
    */
    public function new_subject_can_be_created_with_valid_data(){
        $response = $this->postJson('api/subjects',[
            'name' => 'Математика',
            'type' => 'обавезни',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('subjects', ['name' => 'Математика', 'type' => 'обавезни']);
    }

    /**
    *@test
    */
    public function subject_cannot_be_created_if_exists_subject_with_same_name(){
        $subject = Subject::factory()->create(['name' => 'математика']);

        $response = $this->postJson('api/subjects',[
            'name' => 'математика',
            'type' => 'обавезни',
        ]);

        $response->assertStatus(422);

    }


    /**
    *@test
    */
    public function subject_cannot_be_updated_if_subject_with_that_name_already_exists(){
        $subject1 = Subject::factory()->create(['name' => 'математика']);
        $subject2 = Subject::factory()->create(['name' => 'физика']);

        $response = $this->putJson("api/subjects/{$subject2->id}",[
            'name' => 'математика',
            'type' => 'обавезни',
        ]);

        $response->assertStatus(422);
    }   


   
}
