<?php

namespace App\Models;

use App\Models\Position;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ClassroomSubject extends Pivot
{

    protected $table= 'classroom_subject';


   
    public static function boot(){
        parent::boot();

        static::saved(function($classroomSubject){
            Position::create([
                'positionable_type' => Subject::class,
                'positionable_id' => $classroomSubject->subject_id,
                'classroom_id' => $classroomSubject->classroom_id,
                'position' => Position::where('classroom_id', $classroomSubject->classroom_id)
                                        ->where('positionable_type', Subject::class)
                                        ->max('position') + 1
            ]);
        });

        static::deleted(function($classroomSubject){

            $position = Position::where('positionable_type', Subject::class)
                                ->where('positionable_id', $classroomSubject->subject_id)
                                ->where('classroom_id', $classroomSubject->classroom_id)->first();
        
            $position->delete();
        });

        static::updated(function($classroomSubject){
            $positions = Position::where('classroom_id', $classroomSubject->classroom_id);
            dd($positions);
        });
    }
}
