<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MarkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        switch($this->marks->mark){
            case 5 : 
                $mark = 'одличан(5)';
                break;
            case 4:
                $mark = 'врло добар(4)';
                break;
            case 3:
                $mark = 'добар(3)';
                break;
            case 2:
                $mark = 'довољан(2)';
                break;
            case 1:
                $mark = 'недовољан(1)';
                break;
            }
        //dd($this);
        return [
            'name' => $this->name,
            'type' => $this->type,
            'mark' => $this->marks->mark,
        
            'degree' => $this->marks->degree,
            'descriptive_mark' => $mark
        ];
    }
}
