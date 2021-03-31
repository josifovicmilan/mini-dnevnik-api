<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = ['classroom_number', 'year_started', 'duration','type'];

    public function user(){
        return $this->belongsTo(User::class);
    }


    public function getActiveYearsAttribute(){
        return [
            'I' => $this->year_started .'-'. $this->year_started+1,
            'II' => $this->year_started +1 .'-'. $this->year_started+2,
            'III' => $this->year_started +2 .'-'. $this->year_started+3,
            'IV' => $this->year_started +3 .'-'. $this->year_started+4,
        ];
    }

    public function students(){
        return $this->hasMany(Student::class);
    }

    public function addStudent($student){
        return $this->students()->create($student);

    }

    public function subjects(){
        return $this->belongsToMany(Subject::class)->using(ClassroomSubject::class);
    }

    public function addSubject($subject){
        
        $this->subjects()->attach($subject);
    }
}
