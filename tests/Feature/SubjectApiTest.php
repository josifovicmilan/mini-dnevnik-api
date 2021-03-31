<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

use App\Models\Subject;
use App\Models\Classroom;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubjectApiTest extends TestCase
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
    public function fail_to_view_subjects_if_not_authorized_user(){    
        $this->getJson('/api/subjects')->assertStatus(401);
    }

    /**
    *@test
    */
    public function authorized_user_can_view_subjects(){
    
        $this->actingAs($this->user);
        $subjects = Subject::factory()->count(4)->create();

        $response = $this->getJson('/api/subjects');
        $response->assertJsonStructure([
            '*'=>[
                'name',
                "type",
                ]
        ]);
    }
    /**
    *@test
    */
    public function unauthorized_user_cannot_create_subject(){
        $subject = Subject::factory()->raw();
        $response = $this->postJson('api/subjects', $subject);

        $response->assertStatus(401);

        $this->assertDatabaseMissing('subjects', $subject);
    }
    /**
    *@test
    */
    public function authorized_user_can_create_subject_with_valid_data(){
        $this->actingAs($this->user);
        $subject = Subject::factory()->raw(['name' => 'Математика', 'type' => 'обавезни']);
        $response = $this->postJson('api/subjects', $subject);

        $response->assertStatus(200);

        $this->assertDatabaseHas('subjects', ['name' => 'Математика', 'type' => 'обавезни']);
    }

    /**
    *@test
    */
    public function subject_cannot_be_created_if_exists_subject_with_same_name(){
        $this->actingAs($this->user);
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
        $this->actingAs($this->user);
        $subject1 = Subject::factory()->create(['name' => 'математика']);
        $subject2 = Subject::factory()->create(['name' => 'физика']);

        $response = $this->putJson("api/subjects/{$subject2->id}",[
            'name' => 'математика',
            'type' => 'обавезни',
        ]);

        $response->assertStatus(422);
    }   


   
}
