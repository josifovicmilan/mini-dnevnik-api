<?php

namespace App\Http\Resources;

use App\Models\Position;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {  
        
        // dd(Position::where('classroom_id', $this->pivot->classroom_id)->where('positionable_type', "App\Models\Subject")->where('positionable_id', $this->id)->first()->position);
        
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'position' => Position::where('classroom_id', $this->pivot->classroom_id)
                                  ->where('positionable_type',"App\Models\Subject")
                                  ->where('positionable_id', $this->id)
                                  ->first()
                                  ->position
        ];
    }
}
