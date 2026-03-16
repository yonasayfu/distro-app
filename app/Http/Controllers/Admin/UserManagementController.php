<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\Admin\UpdateUserRolesRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    /**
     * Show the users management page.
     */
    public function index(Request $request): Response
    {
        $search = $request->string('search')->trim()->toString();

        return Inertia::render('admin/Users/Index', [
            'users' => User::query()
                ->with('roles')
                ->when($search !== '', function ($query) use ($search): void {
                    $query->where(function ($userQuery) use ($search): void {
                        $userQuery
                            ->where('name', 'ilike', "%{$search}%")
                            ->orWhere('email', 'ilike', "%{$search}%");
                    });
                })
                ->orderBy('name')
                ->paginate(10)
                ->withQueryString()
                ->through(fn (User $user): array => $this->userSummary($user, $request)),
            'filters' => [
                'search' => $search,
            ],
            'roles' => $this->roleOptions(),
        ]);
    }

    /**
     * Show the user creation page.
     */
    public function create(): Response
    {
        return Inertia::render('admin/Users/Create', [
            'roles' => $this->roleOptions(),
        ]);
    }

    /**
     * Store a new user.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = User::query()->create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
        ]);

        $user->syncRoles($request->validated('roles', []));

        return to_route('users.edit', $user)->with('success', 'User created successfully.');
    }

    /**
     * Show the user edit page.
     */
    public function edit(Request $request, User $user): Response
    {
        $user->load('roles');

        return Inertia::render('admin/Users/Edit', [
            'user' => $this->userSummary($user, $request),
            'roles' => $this->roleOptions(),
        ]);
    }

    /**
     * Update the selected user's details.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($validated['password'] !== null && $validated['password'] !== '') {
            $user->password = Hash::make($validated['password']);
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return to_route('users.edit', $user)->with('success', 'User details updated successfully.');
    }

    /**
     * Update the selected user's assigned roles.
     */
    public function updateRoles(UpdateUserRolesRequest $request, User $user): RedirectResponse
    {
        $roles = $request->validated('roles', []);

        if ($request->user()?->is($user) && ! in_array('Admin', $roles, true)) {
            return to_route('users.edit', $user)->withErrors([
                'roles' => 'You cannot remove your own Admin role from this screen.',
            ]);
        }

        $user->syncRoles($roles);

        return to_route('users.edit', $user)->with('success', 'User roles updated successfully.');
    }

    /**
     * Delete the selected user.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user()?->is($user)) {
            return to_route('users.index')->with('error', 'You cannot delete the currently signed-in user.');
        }

        $user->delete();

        return to_route('users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Normalize a user for admin page props.
     *
     * @return array{id: int, name: string, email: string, roles: array<int, string>, isCurrentUser: bool, emailVerifiedAt: string|null, createdAt: string|null}
     */
    private function userSummary(User $user, Request $request): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->roles->pluck('name')->values()->all(),
            'isCurrentUser' => $request->user()?->is($user) ?? false,
            'emailVerifiedAt' => $user->email_verified_at?->toDateTimeString(),
            'createdAt' => $user->created_at?->toDateTimeString(),
        ];
    }

    /**
     * Get role options for form checkboxes.
     *
     * @return array<int, array{name: string, label: string, description: string|null, usersCount: int}>
     */
    private function roleOptions(): array
    {
        return Role::query()
            ->withCount('users')
            ->orderBy('name')
            ->get()
            ->map(fn (Role $role): array => [
                'name' => $role->name,
                'label' => $role->name,
                'description' => $role->description,
                'usersCount' => $role->users_count,
            ])
            ->values()
            ->all();
    }
}
