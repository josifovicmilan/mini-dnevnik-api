<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Classroom;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create();
        $user2= User::factory()->admin()->create(['email' => 'admin@test.com']);
        $classroom = Classroom::factory()->create(['user_id' => $user->id]);
        $subjects = Subject::factory()->count(3)->create();
        Student::factory()->count(5)->create(['classroom_id' => $classroom->id])->each(function($student) use ($subjects){
            $student->marks()->attach($subjects,['mark' => mt_rand(1,5), 'degree' => 'I']);
        });
        
    }
}
