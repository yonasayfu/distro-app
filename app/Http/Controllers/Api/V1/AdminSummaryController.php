<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\AdminSummaryResource;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Permission\Models\Role;

class AdminSummaryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResource
    {
        return new AdminSummaryResource((object) [
            'counts' => [
                'users' => User::query()->count(),
                'verified_users' => User::query()->whereNotNull('email_verified_at')->count(),
                'roles' => Role::query()->count(),
                'unread_notifications' => $request->user()->unreadNotifications()->count(),
                'activity_logs' => ActivityLog::query()->count(),
            ],
            'recent_users' => User::query()
                ->with('roles')
                ->latest()
                ->limit(5)
                ->get(),
            'role_breakdown' => Role::query()
                ->orderBy('name')
                ->get()
                ->map(fn (Role $role): array => [
                    'name' => $role->name,
                    'description' => $role->description,
                    'users_count' => User::role($role->name)->count(),
                ])
                ->values()
                ->all(),
        ]);
    }
}
