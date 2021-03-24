<?php

namespace App\Http\Resources;

use App\Http\Resources\MarkResource;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return MarkResource::collection($this->whenLoaded('marks'));

    }
}
