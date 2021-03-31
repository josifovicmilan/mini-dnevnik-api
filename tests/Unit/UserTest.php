<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Subject;
use App\Models\Classroom;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
    *@test
    */
    public function user_can_have_classrooms(){
        $user = User::factory()->create();


        $this->assertInstanceOf(Collection::class,  $user->classrooms);
    }
}
