<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\StudentAlreadyGradedException;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function personalData(){
        return $this->hasOne(PersonalData::class);
    }

    public function primarySchool(){
        return $this->hasOne(PrimarySchoolData::class);
    }

    public function marks(){
        return $this->belongsToMany(Subject::class, 'marks')->as('marks')->withPivot(['mark', 'degree']);
    }

    public function grade($subject_id, $mark, $degree){
        if($this->marks()->where('subject_id', $subject_id)->where('degree', $degree)->exists()){
            throw new StudentAlreadyGradedException('Student already has a mark');
        }
        $this->marks()->attach($subject_id, ['mark'=>$mark, 'degree' => $degree]);
    }

    public function gradeMany($marks){
        foreach($marks as $mark){
            $this->grade($mark->subject_id, $mark->mark, $mark->degree);
        }
    }

    public function showMarks(){
        return $this->with('marks');
    }

}
