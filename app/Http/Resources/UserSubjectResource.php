<?php

namespace App\Http\Resources;

use App\Http\Resources\PositionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSubjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return PositionResource::collection($this->whenLoaded('subjects'));
    
    }
}
