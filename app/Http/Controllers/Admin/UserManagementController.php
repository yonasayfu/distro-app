<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRolesRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    /**
     * Show the users management page.
     */
    public function index(): Response
    {
        return Inertia::render('admin/Users/Index', [
            'users' => User::query()
                ->with('roles')
                ->orderBy('name')
                ->get()
                ->map(fn (User $user): array => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->roles->pluck('name')->values()->all(),
                    'isCurrentUser' => request()->user()?->is($user) ?? false,
                    'emailVerifiedAt' => $user->email_verified_at?->toDateTimeString(),
                    'createdAt' => $user->created_at?->toDateTimeString(),
                ])
                ->values()
                ->all(),
            'roles' => Role::query()
                ->withCount('users')
                ->orderBy('name')
                ->get()
                ->map(fn (Role $role): array => [
                    'name' => $role->name,
                    'label' => $role->name,
                    'usersCount' => $role->users_count,
                ])
                ->values()
                ->all(),
        ]);
    }

    /**
     * Update the selected user's assigned roles.
     */
    public function updateRoles(UpdateUserRolesRequest $request, User $user): RedirectResponse
    {
        $roles = $request->validated('roles', []);

        if ($request->user()?->is($user) && ! in_array('Admin', $roles, true)) {
            return to_route('users.index')->withErrors([
                'roles' => 'You cannot remove your own Admin role from this screen.',
            ]);
        }

        $user->syncRoles($roles);

        return to_route('users.index');
    }
}
