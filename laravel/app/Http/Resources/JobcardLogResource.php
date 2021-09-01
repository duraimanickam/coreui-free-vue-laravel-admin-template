<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class JobcardLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => $this->user->name,
            'description' => $this->description,
            'status' => $this->status,
            'date' => Carbon::parse($this->created_at)->format('d-m-Y'),
            'updated_at' => $this->updated_at,
        ];
    }
}
