<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function positionable(){
    
        return $this->morphTo();
    }

    public function scopeInClassroom($query, $classroom){
    
        return $query->where('classroom_id', $classroom);
    }
}
