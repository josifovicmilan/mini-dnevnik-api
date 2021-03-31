<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $guarded = [];
    

    public function next(){
        return Subject::where('position', '>', $this->position)->orderBy('position', 'asc')->first();
    }

    public function prev(){
        return Subject::where('position', '<', $this->position)->orderBy('position', 'asc')->first();
    }

    public static function swap(Subject $subject1, Subject $subject2){
        $helperPosition = $subject1->position;
        $subject1->update(['position' => $subject2->position]);
        $subject2->update(['position' => $helperPosition]);
    }
    
    public function position(){
        return $this->morphOne(Position::class, 'positionable');
    }

    public function addPosition($classroom){

        return $this->position()->create([
            'positionable_type' => Subject::class,
            'positionable_id' => $this->id,
            'classroom_id' => $classroom->id,
            'position' => Position::max('position') + 1,
        ]);
    }

    
}
