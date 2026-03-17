<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportCenterController extends Controller
{
    /**
     * Display reusable export and print actions.
     */
    public function index(Request $request): Response
    {
        $resources = [];

        if ($request->user()?->can('users.view')) {
            $resources[] = [
                'key' => 'users-csv',
                'title' => 'Users export',
                'description' => 'Download a CSV snapshot of users and their assigned roles.',
                'href' => route('exports.users.csv'),
                'actionLabel' => 'Download CSV',
                'format' => 'CSV',
            ];
        }

        $resources[] = [
            'key' => 'workspace-print',
            'title' => 'Workspace summary print',
            'description' => 'Open a print-friendly summary of core workspace counts and recent activity.',
            'href' => route('exports.summary.print'),
            'actionLabel' => 'Open print view',
            'format' => 'Print',
        ];

        return Inertia::render('exports/Index', [
            'resources' => $resources,
        ]);
    }

    /**
     * Download a CSV export of users.
     */
    public function usersCsv(Request $request): StreamedResponse
    {
        ActivityLogger::record(
            actor: $request->user(),
            event: 'exports.users-csv',
            description: 'Downloaded the users CSV export.',
            properties: [
                'format' => 'csv',
            ],
            request: $request,
        );

        return response()->streamDownload(function (): void {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Name', 'Email', 'Roles', 'Email Verified At', 'Created At']);

            User::query()
                ->with('roles')
                ->orderBy('name')
                ->get()
                ->each(function (User $user) use ($handle): void {
                    fputcsv($handle, [
                        $user->name,
                        $user->email,
                        $user->roles->pluck('name')->join(', '),
                        $user->email_verified_at?->toDateTimeString(),
                        $user->created_at?->toDateTimeString(),
                    ]);
                });

            fclose($handle);
        }, 'users-export.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Show a print-friendly summary page.
     */
    public function printSummary(Request $request): Response
    {
        ActivityLogger::record(
            actor: $request->user(),
            event: 'exports.summary-print',
            description: 'Opened the workspace print summary.',
            properties: [
                'format' => 'print',
            ],
            request: $request,
        );

        return Inertia::render('exports/PrintSummary', [
            'summary' => [
                'counts' => [
                    'users' => User::query()->count(),
                    'roles' => Role::query()->count(),
                    'unreadNotifications' => $request->user()?->unreadNotifications()->count() ?? 0,
                    'activityLogs' => ActivityLog::query()->count(),
                ],
                'recentUsers' => User::query()
                    ->with('roles')
                    ->latest()
                    ->limit(5)
                    ->get()
                    ->map(fn (User $user): array => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'roles' => $user->roles->pluck('name')->values()->all(),
                        'createdAt' => $user->created_at?->toISOString(),
                    ])
                    ->values()
                    ->all(),
                'recentEvents' => ActivityLog::query()
                    ->latest('created_at')
                    ->limit(6)
                    ->get()
                    ->map(fn (ActivityLog $log): array => [
                        'id' => $log->id,
                        'event' => $log->event,
                        'description' => $log->description,
                        'createdAt' => $log->created_at?->toISOString(),
                    ])
                    ->values()
                    ->all(),
            ],
        ]);
    }
}
