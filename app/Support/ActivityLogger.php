<?php

namespace App\Support;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ActivityLogger
{
    public static function record(
        ?User $actor,
        string $event,
        string $description,
        ?Model $subject = null,
        array $properties = [],
        ?Request $request = null,
    ): ActivityLog {
        return ActivityLog::query()->create([
            'actor_id' => $actor?->id,
            'event' => $event,
            'description' => $description,
            'subject_type' => $subject?->getMorphClass(),
            'subject_id' => $subject?->getKey(),
            'properties' => $properties,
            'ip_address' => $request?->ip(),
            'created_at' => now(),
        ]);
    }
}
