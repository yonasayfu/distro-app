<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateRoleRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleManagementController extends Controller
{
    /**
     * Show the role management page.
     */
    public function index(): Response
    {
        return Inertia::render('admin/Roles/Index', [
            'roles' => Role::query()
                ->with('permissions')
                ->withCount('users')
                ->orderBy('name')
                ->get()
                ->map(fn (Role $role): array => [
                    'id' => $role->id,
                    'name' => $role->name,
                    'permissions' => $role->permissions->pluck('name')->values()->all(),
                    'usersCount' => $role->users_count,
                    'editable' => $role->name !== 'Admin',
                ])
                ->values()
                ->all(),
            'permissionGroups' => Permission::query()
                ->orderBy('name')
                ->get()
                ->groupBy(fn (Permission $permission): string => str($permission->name)->before('.')->toString())
                ->map(fn (Collection $permissions, string $group): array => [
                    'key' => $group,
                    'title' => str($group)->replace('-', ' ')->title()->toString(),
                    'permissions' => $permissions->map(fn (Permission $permission): array => [
                        'name' => $permission->name,
                        'label' => $this->permissionLabel($permission->name),
                        'description' => $this->permissionDescription($permission->name),
                    ])->values()->all(),
                ])
                ->values()
                ->all(),
        ]);
    }

    /**
     * Update the selected role's permissions.
     */
    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        if ($role->name === 'Admin') {
            return to_route('roles.index')->withErrors([
                'permissions' => 'The Admin role is managed automatically and cannot be edited from this screen.',
            ]);
        }

        $role->syncPermissions($request->validated('permissions', []));

        return to_route('roles.index');
    }

    private function permissionLabel(string $permission): string
    {
        return match (str($permission)->after('.')->toString()) {
            'view' => 'View access',
            'create' => 'Create records',
            'update' => 'Update records',
            'delete' => 'Delete records',
            default => str($permission)->after('.')->replace('-', ' ')->title()->toString(),
        };
    }

    private function permissionDescription(string $permission): string
    {
        return match ($permission) {
            'dashboard.view' => 'Allows the role to enter the dashboard workspace.',
            'users.view' => 'Shows the users module and allows access to the users index page.',
            'users.create' => 'Allows creating new users when user creation is added later.',
            'users.update' => 'Allows editing user details, roles, and future user actions.',
            'users.delete' => 'Allows deleting users when destructive user actions are enabled.',
            'roles.view' => 'Shows the roles module and allows access to role management.',
            'roles.create' => 'Allows creating new roles when custom role creation is added.',
            'roles.update' => 'Allows changing role permissions and future role settings.',
            'roles.delete' => 'Allows deleting removable roles when enabled later.',
            'notifications.view' => 'Shows shared notification features for the signed-in user.',
            'activity-logs.view' => 'Allows access to future activity and audit log pages.',
            default => 'Controls access to this capability across routes, sidebar items, and UI actions.',
        };
    }
}
