<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = ['classroom_number', 'year_started', 'duration','type'];

    public function students(){
        return $this->hasMany(Student::class);
    }

    public function getActiveYearsAttribute(){
        return [
            'I' => $this->year_started .'-'. $this->year_started+1,
            'II' => $this->year_started +1 .'-'. $this->year_started+2,
            'III' => $this->year_started +2 .'-'. $this->year_started+3,
            'IV' => $this->year_started +3 .'-'. $this->year_started+4,
        ];
    }
}
