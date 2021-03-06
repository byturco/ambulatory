<?php

namespace Ambulatory\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'preferred_date_time' => $this->preferred_date_time,
            'actual_end_date_time' => $this->actual_end_date_time,
            'description' => $this->description,
            'is_active' => (bool) $this->is_active,
            'medical_form' => new MedicalFormResource($this->whenLoaded('medicalForm')),
            'doctor_schedule' => new ScheduleResource($this->whenLoaded('schedule')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
