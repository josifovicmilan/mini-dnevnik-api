<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Subject;
use App\Models\Position;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PositionTest extends TestCase
{
   use RefreshDatabase;


   protected $user;
   protected $classroom;

   protected function setUp():void{
   
      parent::setUp();
      $this->user = User::factory()->create();
      $this->classroom = Classroom::factory()->create(['user_id' => $this->user->id]);
   }

   /**
   *@test
   */
   public function position_belongs_to_classroom_subject(){
   
      
      $subject1 = Subject::factory()->create();

      $this->classroom->addSubject($subject1);


      $classroom2 = Classroom::factory()->create(['classroom_number' => 10, 'user_id' => $this->user->id]);
      $subject2 = Subject::factory()->create();

      $classroom2->addSubject($subject2);

      $position = Position::inClassroom($this->classroom)->get();

      $this->assertCount(1, $position);
   }


    /**
    *@test
    */
    public function when_subject_is_added_to_classroom_it_receives_a_position(){
    
       

      $subjects =Subject::factory()->count(5)->create();

      $this->classroom->addSubjects($subjects);

      foreach($subjects as $subject){
          $this->assertDatabaseHas('positions', [
              'positionable_type' => Subject::class, 
              'positionable_id' => $subject->id, 
              'classroom_id' => $this->classroom->id
              ]);
      }
  }


  /**
  *@test
  */
  public function when_subject_is_removed_from_classroom_its_position_is_removed_and_other_subject_are_reordered(){
  
      $subjects = Subject::factory()->count(5)->create();

      $this->classroom->addSubjects($subjects);

   
      $positions = Position::where('classroom_id', $this->classroom->id)
                              ->where('positionable_type', Subject::class)->get();
      $this->assertCount(5, $positions);


      $this->classroom->removeSubject(Subject::find(3));

      $positions = Position::where('classroom_id', $this->classroom->id)
                              ->where('positionable_type', Subject::class)->get();

      $this->assertCount(4, $positions);

      $this->assertEquals(4, $positions->fresh()->max('position'));
      
  }


  /**
  *@test
  */
  public function when_position_of_subject_is_updated_all_other_positions_are_reordered(){
  
      $subjects = Subject::factory()->count(5)->create();

      $this->classroom->addSubjects($subjects);

      $matematika = $this->classroom->subjects()->find(2);
      $srpski = $this->classroom->subjects()->find(4);

      
      //dd($positions);
      
     $matematika->updatePosition($srpski);

     $this->assertEquals(4, Position::find(2)->position);
     $this->assertEquals(3, Position::find(4)->position);

  }
}
