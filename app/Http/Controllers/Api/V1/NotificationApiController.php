<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\NotificationResource;
use App\Support\ActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Notifications\DatabaseNotification;

class NotificationApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $readFilter = $request->string('read')->trim()->toString();

        $notifications = $request->user()
            ->notifications()
            ->when($readFilter === 'unread', fn ($query) => $query->whereNull('read_at'))
            ->when($readFilter === 'read', fn ($query) => $query->whereNotNull('read_at'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return NotificationResource::collection($notifications)->additional([
            'meta' => [
                'filters' => [
                    'read' => $readFilter,
                ],
                'unread_count' => $request->user()->unreadNotifications()->count(),
            ],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $notification): NotificationResource
    {
        /** @var DatabaseNotification $entry */
        $entry = $request->user()->notifications()->findOrFail($notification);

        return new NotificationResource($entry);
    }

    /**
     * Mark a notification as read.
     */
    public function update(Request $request, string $notification): JsonResponse
    {
        /** @var DatabaseNotification $entry */
        $entry = $request->user()->notifications()->findOrFail($notification);

        if ($entry->read_at === null) {
            $entry->markAsRead();

            ActivityLogger::record(
                actor: $request->user(),
                event: 'notifications.read',
                description: 'Marked a notification as read via API.',
                properties: [
                    'notification_id' => $entry->id,
                    'channel' => 'api',
                ],
                request: $request,
            );
        }

        return response()->json([
            'message' => 'Notification marked as read.',
            'notification' => new NotificationResource($entry->fresh()),
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function destroy(Request $request): JsonResponse
    {
        $unread = $request->user()->unreadNotifications()->get();

        $unread->markAsRead();

        ActivityLogger::record(
            actor: $request->user(),
            event: 'notifications.read-all',
            description: 'Marked all notifications as read via API.',
            properties: [
                'count' => $unread->count(),
                'channel' => 'api',
            ],
            request: $request,
        );

        return response()->json([
            'message' => 'All notifications marked as read.',
            'unread_count' => 0,
        ]);
    }
}
