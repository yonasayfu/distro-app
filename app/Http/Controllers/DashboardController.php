<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\ImportRun;
use App\Models\Media;
use App\Models\Page;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Dashboard', [
            'metrics' => [
                [
                    'key' => 'pages',
                    'label' => 'Pages',
                    'value' => Page::withTrashed()->count(),
                    'description' => 'Public content records including archived or deleted items.',
                    'tone' => 'amber',
                ],
                [
                    'key' => 'media',
                    'label' => 'Media files',
                    'value' => Media::query()->count(),
                    'description' => 'Shared business media ready for later attachment reuse.',
                    'tone' => 'sky',
                ],
                [
                    'key' => 'imports',
                    'label' => 'Import runs',
                    'value' => ImportRun::query()->count(),
                    'description' => 'Previewed and completed bulk-intake operations.',
                    'tone' => 'emerald',
                ],
                [
                    'key' => 'activeUsers',
                    'label' => 'Active users',
                    'value' => User::query()->count(),
                    'description' => 'Signed-in operators and role-managed users in the workspace.',
                    'tone' => 'violet',
                ],
            ],
            'recentActivity' => ActivityLog::query()
                ->latest('created_at')
                ->limit(6)
                ->get()
                ->map(fn (ActivityLog $log): array => [
                    'id' => $log->id,
                    'event' => $log->event,
                    'description' => $log->description,
                    'createdAt' => $log->created_at?->toDateTimeString(),
                ])
                ->values()
                ->all(),
            'reportHighlights' => [
                'publishedPages' => Page::query()->where('status', 'published')->count(),
                'reviewPages' => Page::query()->where('status', 'review')->count(),
                'deletedPages' => Page::onlyTrashed()->count(),
                'completedImports' => ImportRun::query()->where('status', 'completed')->count(),
            ],
        ]);
    }
}
