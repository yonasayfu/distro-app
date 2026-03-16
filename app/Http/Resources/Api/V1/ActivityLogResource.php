<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'event' => $this->resource->event,
            'description' => $this->resource->description,
            'actor' => $this->resource->actor?->name,
            'actor_email' => $this->resource->actor?->email,
            'subject_type' => $this->resource->subject_type,
            'subject_id' => $this->resource->subject_id,
            'ip_address' => $this->resource->ip_address,
            'properties' => $this->resource->properties ?? [],
            'created_at' => $this->resource->created_at?->toISOString(),
        ];
    }
}
