<?php

namespace App\Http\Resources;

use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class Notes extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'ID'          => $this->id,
            'Author'      => User::find($this->owner_id)->name,
            'Note'        => $this->note,
            'Description' => $this->description,
            'Status'      => $this->status,
            'Tags'        => Tags::collection($this->whenLoaded('tag')),

        ];
    }
}






