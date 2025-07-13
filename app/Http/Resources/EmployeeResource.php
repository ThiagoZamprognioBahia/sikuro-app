<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'first_name' => $this->first_name,
            'last_name'  => $this->last_name,
            'company_id' => $this->company_id,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'company'    => new CompanyResource($this->whenLoaded('company')),
        ];
    }
}