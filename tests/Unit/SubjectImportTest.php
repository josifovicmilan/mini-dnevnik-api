<?php

namespace Tests\Unit;

use Tests\TestCase;

use App\Imports\SubjectsImport;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubjectImportTest extends TestCase
{

    use RefreshDatabase;

 /**
    *@test
    */
    public function new_excel_file_for_uploading_subjects_can_be_imported(){
        $this->withoutExceptionHandling();
        $file = UploadedFile::fake()->create('subjects.xlsx');
        
        Excel::fake();


        $response = $this->postJson('api/subjects/import',[
            'file' => $file
        ]);
        //$response->assertStatus(200);

        Excel::assertImported('subjects.xlsx');
        
    }
}
