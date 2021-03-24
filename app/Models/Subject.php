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
}
