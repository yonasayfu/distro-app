<?php

namespace App\Http\Controllers;

use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Inertia\Inertia;
use Inertia\Response;

class NotificationController extends Controller
{
    /**
     * Display the authenticated user's notifications.
     */
    public function index(Request $request): Response
    {
        $readFilter = $request->string('read')->trim()->toString();

        $notifications = $request->user()
            ->notifications()
            ->when($readFilter === 'unread', fn ($query) => $query->whereNull('read_at'))
            ->when($readFilter === 'read', fn ($query) => $query->whereNotNull('read_at'))
            ->latest()
            ->paginate(10)
            ->withQueryString()
            ->through(fn (DatabaseNotification $notification): array => [
                'id' => $notification->id,
                'title' => $notification->data['title'] ?? 'Notification',
                'message' => $notification->data['message'] ?? '',
                'actionUrl' => $notification->data['action_url'] ?? null,
                'actionLabel' => $notification->data['action_label'] ?? null,
                'level' => $notification->data['level'] ?? 'info',
                'readAt' => $notification->read_at?->toISOString(),
                'createdAt' => $notification->created_at?->toISOString(),
            ]);

        return Inertia::render('notifications/Index', [
            'notifications' => $notifications,
            'filters' => [
                'read' => $readFilter,
            ],
            'stats' => [
                'unreadCount' => $request->user()->unreadNotifications()->count(),
                'totalCount' => $request->user()->notifications()->count(),
            ],
        ]);
    }

    /**
     * Mark a notification as read.
     */
    public function markRead(Request $request, string $notification): RedirectResponse
    {
        $entry = $request->user()->notifications()->findOrFail($notification);

        if ($entry->read_at === null) {
            $entry->markAsRead();

            ActivityLogger::record(
                actor: $request->user(),
                event: 'notifications.read',
                description: 'Marked a notification as read.',
                subject: null,
                properties: [
                    'notification_id' => $entry->id,
                ],
                request: $request,
            );
        }

        return to_route('notifications.index')->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllRead(Request $request): RedirectResponse
    {
        $unread = $request->user()->unreadNotifications()->get();

        $unread->markAsRead();

        ActivityLogger::record(
            actor: $request->user(),
            event: 'notifications.read-all',
            description: 'Marked all notifications as read.',
            properties: [
                'count' => $unread->count(),
            ],
            request: $request,
        );

        return to_route('notifications.index')->with('success', 'All notifications marked as read.');
    }
}
