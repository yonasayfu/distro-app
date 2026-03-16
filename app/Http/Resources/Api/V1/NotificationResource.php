<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'title' => $this->resource->data['title'] ?? 'Notification',
            'message' => $this->resource->data['message'] ?? '',
            'action_url' => $this->resource->data['action_url'] ?? null,
            'action_label' => $this->resource->data['action_label'] ?? null,
            'level' => $this->resource->data['level'] ?? 'info',
            'read_at' => $this->resource->read_at?->toISOString(),
            'created_at' => $this->resource->created_at?->toISOString(),
        ];
    }
}
