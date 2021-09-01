<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class JobcardResource extends JsonResource
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
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'zone' => ($this->zone)?$this->zone->name:'',
            'company' => ($this->company)?$this->company->name:'',
            'facility' => ($this->facility)?$this->facility->name:'',
            'building' => ($this->building)?$this->building->name:'',
            'floor' => ($this->floor)?$this->floor->name:'',
            'estimated_time' => $this->estimated_time,
            'service_type' => $this->service_type,
            'contact_person' => $this->contact_person,
            'phone' => $this->phone,
            'priority' => $this->priority,
            'assigned_to' => ($this->assignedTo)?$this->assignedTo->name:'',
            'status' => $this->status,
            'description' => $this->description,
            'logs' => JobcardLogResource::collection($this->logs),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
