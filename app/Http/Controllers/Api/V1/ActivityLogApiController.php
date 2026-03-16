<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ActivityLogResource;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ActivityLogApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $search = $request->string('search')->trim()->toString();
        $event = $request->string('event')->trim()->toString();

        $logs = ActivityLog::query()
            ->with('actor')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($logQuery) use ($search): void {
                    $logQuery
                        ->where('description', 'ilike', "%{$search}%")
                        ->orWhere('event', 'ilike', "%{$search}%");
                });
            })
            ->when($event !== '', fn ($query) => $query->where('event', $event))
            ->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        return ActivityLogResource::collection($logs)->additional([
            'meta' => [
                'filters' => [
                    'search' => $search,
                    'event' => $event,
                ],
                'event_options' => ActivityLog::query()
                    ->select('event')
                    ->distinct()
                    ->orderBy('event')
                    ->pluck('event')
                    ->values()
                    ->all(),
            ],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ActivityLog $activityLog): ActivityLogResource
    {
        $activityLog->load('actor');

        return new ActivityLogResource($activityLog);
    }
}
