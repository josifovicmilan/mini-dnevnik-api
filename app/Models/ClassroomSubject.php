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
            $subject = Subject::find($classroomSubject->subject_id)->first();
            $position = Position::max('position');
            Position::create([
                'positionable_type' => Subject::class,
                'positionable_id' => $subject->id,
                'classroom_id' => $classroomSubject->classroom_id,
                'position' => $position + 1
            ]);
        });
    }
}
