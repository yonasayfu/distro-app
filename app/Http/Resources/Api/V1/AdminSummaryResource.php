<?php

namespace App\Http\Resources\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminSummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'counts' => $this->resource->counts,
            'recent_users' => collect($this->resource->recent_users)
                ->map(fn (User $user): array => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->roles->pluck('name')->values()->all(),
                    'created_at' => $user->created_at?->toISOString(),
                ])
                ->values()
                ->all(),
            'role_breakdown' => $this->resource->role_breakdown,
        ];
    }
}
