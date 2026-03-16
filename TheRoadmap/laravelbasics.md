# Laravel Basics

This is the running learning log for this project.

Rule for future work:

- after each implemented task or meaningful batch of work, add a short entry here
- explain what was changed
- explain which Laravel feature or package we used
- explain why that approach fits Laravel

The goal is not just to finish the boilerplate. The goal is to understand how Laravel applications are structured and why the code is written that way.

## How to Use This File

For every completed task, record:

1. What we built
2. Which Laravel feature or package we used
3. Which files were important
4. Why the implementation was done this way
5. What to remember for future projects

## Current Project Stack

This starter currently uses:

- Laravel 12
- Inertia.js
- Vue 3
- Fortify
- Wayfinder
- Pest
- Pint
- Boost

## Laravel Structure in This Repo

Important folders:

- `app/`: application logic such as controllers, models, middleware, providers, requests, and actions
- `routes/`: route definitions
- `resources/js/`: Inertia Vue frontend
- `config/`: framework and package configuration
- `database/`: migrations, factories, and seeders
- `tests/`: Pest tests

Important project files:

- `bootstrap/app.php`: application bootstrapping, middleware registration, and routing setup in Laravel 12
- `routes/web.php`: browser routes
- `routes/settings.php`: profile, security, and appearance routes
- `app/Http/Middleware/HandleInertiaRequests.php`: shared data passed from Laravel to Inertia pages
- `app/Providers/FortifyServiceProvider.php`: auth view wiring and rate limiting

## Key Laravel Concepts Already Present

### 1. Routes

Routes map URLs to controllers or Inertia pages.

Examples in this repo:

- `/` returns the welcome page through Inertia
- `/dashboard` returns the dashboard page for authenticated and verified users
- `/settings/...` handles profile, security, and appearance

What to remember:

- routes define the public shape of your app
- middleware on routes controls access
- named routes make navigation more maintainable

### 2. Middleware

Middleware sits between the request and the response.

Examples already used:

- `auth`
- `verified`
- custom Inertia-sharing middleware

What to remember:

- use middleware for cross-cutting rules like authentication, verification, throttling, and shared request behavior

### 3. Controllers

Controllers handle request logic.

In this repo the settings pages are handled by controllers like:

- `ProfileController`
- `SecurityController`

What to remember:

- keep controllers thin
- move validation to Form Requests
- move complex business logic to services or action classes when needed

### 4. Fortify

Fortify provides backend auth features without forcing Blade UI.

In this repo it powers:

- login
- register
- password reset
- email verification
- two-factor authentication

What to remember:

- Fortify handles auth mechanics
- Inertia pages provide the frontend
- `FortifyServiceProvider` connects Fortify routes to your Vue pages

### 5. Inertia

Inertia lets Laravel return Vue pages without building a separate SPA API for everything.

What to remember:

- Laravel remains the routing and backend source of truth
- Vue renders the page component
- shared props can be added through `HandleInertiaRequests`

### 6. Wayfinder

Wayfinder generates route helpers for TypeScript so frontend links and form actions stay aligned with Laravel routes.

What to remember:

- it reduces hardcoded frontend URLs
- it helps keep backend and frontend route usage consistent

### 7. Pest

Pest is the testing framework used in this project.

What to remember:

- feature tests are usually the most useful in Laravel apps
- tests prove behavior, not just syntax

## Entry 001: Phase 0 Documentation Setup

### What we did

We created the standalone planning folder `TheRoadmap/` and added the roadmap, task list, UI template guide, reference carryover notes, MCP guidance, git workflow guidance, and this Laravel learning file.

### Laravel concepts involved

- project structure
- package awareness
- environment and setup readiness
- architecture planning before code changes

### Why this matters

A Laravel boilerplate is not only code. It is also conventions, workflow, and repeatable structure. If the planning layer is weak, the codebase drifts quickly.

### Files involved

- `TheRoadmap/README.md`
- `TheRoadmap/BoilerplateRoadmap.md`
- `TheRoadmap/BoilerplateTaskList.md`
- `TheRoadmap/followTemplate.md`
- `TheRoadmap/ReferenceCarryover.md`
- `TheRoadmap/mcpguidance.md`
- `TheRoadmap/gitguidance.md`
- `TheRoadmap/laravelbasics.md`

### What to remember

- before building modules, define the app shape
- keep the starter domain-neutral
- write down why something exists so future work stays consistent

## Entry 002: Phase 1 Shell Foundation Batch 1

### What we did

We completed the first real Phase 1 shell batch:

- replaced the placeholder dashboard with a real starter workspace page
- centralized sidebar and resource navigation into one shared navigation file
- removed the old starter-kit repository link pattern from the shell
- updated branding so the shell reads from the Laravel app name instead of hardcoding "Laravel Starter Kit"

### Code-level change log

This section records what the code looked like before, what changed, and why.

#### 1. `resources/js/navigation/app.ts`

Before:

- there was no shared navigation file
- sidebar items were declared directly inside `AppSidebar.vue`
- the shell structure was tied to one component instead of a reusable source

After:

- we created a dedicated navigation file
- we added grouped navigation for:
  - `Workspace`
  - `Account`
  - `Resources`
- we used Wayfinder route helpers like `dashboard()`, `editProfile()`, `editSecurity()`, and `editAppearance()`

Representative diff:

```ts
+ export const appNavigation: NavGroup[] = [
+   {
+     title: 'Workspace',
+     items: [{ title: 'Dashboard', href: dashboard(), icon: LayoutGrid }],
+   },
+   {
+     title: 'Account',
+     items: [
+       { title: 'Profile', href: editProfile(), icon: UserCog },
+       { title: 'Security', href: editSecurity(), icon: ShieldCheck },
+       { title: 'Appearance', href: editAppearance(), icon: Palette },
+     ],
+   },
+ ];
```

Why:

- navigation should live in one place
- future RBAC will need one source of truth for what appears in the sidebar
- this reduces duplication and makes the shell maintainable

#### 2. `resources/js/components/AppSidebar.vue`

Before:

- `AppSidebar.vue` defined its own `mainNavItems`
- it also defined its own `footerNavItems`
- the footer pointed to starter-kit GitHub and starter-kit docs

Before example:

```ts
- const mainNavItems: NavItem[] = [
-   { title: 'Dashboard', href: dashboard(), icon: LayoutGrid },
- ];
-
- const footerNavItems: NavItem[] = [
-   { title: 'Repository', href: 'https://github.com/laravel/vue-starter-kit', icon: FolderGit2 },
-   { title: 'Documentation', href: 'https://laravel.com/docs/starter-kits#vue', icon: BookOpen },
- ];
```

After:

- `AppSidebar.vue` now imports shared data from `@/navigation/app`
- it passes `appNavigation` into `NavMain`
- it passes `appResourceLinks` into `NavFooter`
- we also improved the sidebar surface with border, background, and blur styling

After example:

```ts
+ import { appNavigation, appResourceLinks } from '@/navigation/app';
...
+ <NavMain :items="appNavigation" />
+ <NavFooter :items="appResourceLinks" />
```

Why:

- the sidebar component should render navigation, not own navigation
- this makes later permission filtering much easier
- it also removes leftover starter-kit branding

#### 3. `resources/js/components/NavMain.vue`

Before:

- `NavMain.vue` expected a flat array of `NavItem`
- it always rendered a single hardcoded group label: `Platform`

Before example:

```ts
- defineProps<{ items: NavItem[] }>();
...
- <SidebarGroupLabel>Platform</SidebarGroupLabel>
- <SidebarMenuItem v-for="item in items" :key="item.title">
```

After:

- `NavMain.vue` now expects grouped navigation using `NavGroup[]`
- it loops over each group and renders the group title dynamically

After example:

```ts
+ defineProps<{ items: NavGroup[] }>();
...
+ <SidebarGroup v-for="group in items" :key="group.title">
+   <SidebarGroupLabel>{{ group.title }}</SidebarGroupLabel>
+   <SidebarMenuItem v-for="item in group.items" :key="`${group.title}-${item.title}`">
```

Why:

- real admin sidebars usually need multiple sections
- a grouped structure is more scalable than a single list

#### 4. `resources/js/components/NavFooter.vue`

Before:

- `NavFooter.vue` also expected a flat array of `NavItem`
- it rendered raw footer links without grouping

After:

- it now accepts `NavGroup[]`
- it renders a `SidebarGroupLabel` for each group
- it can now handle grouped resource links the same way as the main sidebar

Why:

- this keeps the footer navigation consistent with the main navigation structure
- it avoids having two different mental models for nav data

#### 5. `resources/js/types/navigation.ts`

Before:

- only `NavItem` existed

After:

- we added `NavGroup`

Code:

```ts
+ export type NavGroup = {
+   title: string;
+   items: NavItem[];
+ };
```

Why:

- types should match the actual UI structure
- once the shell became grouped, the type system needed to express that correctly

## Entry 006: Phase 4 RBAC Management Batch 1

### What we did

We turned the placeholder `Roles` and `Users` pages into real RBAC management screens:

- roles can now be edited from the UI using permission checkboxes
- users can now be assigned roles from the UI
- Laravel now validates those updates with dedicated Form Requests
- route protection and sidebar visibility stay tied to the same permission system

This is the point where RBAC stopped being only setup code and became an actual admin workflow.

### Code-level change log

#### 1. `routes/web.php`

Before:

- `users.index` and `roles.index` were plain `Route::inertia(...)` placeholder pages
- there were no update routes for changing permissions or user roles

Before example:

```php
- Route::inertia('admin/users', 'admin/Users/Index')
-     ->middleware('permission:users.view')
-     ->name('users.index');
-
- Route::inertia('admin/roles', 'admin/Roles/Index')
-     ->middleware('permission:roles.view')
-     ->name('roles.index');
```

After:

- we replaced the placeholder routes with controller-backed routes
- we added a `PUT` route for role permission updates
- we added a `PUT` route for user role updates

After example:

```php
+ Route::get('admin/users', [UserManagementController::class, 'index'])
+     ->middleware('permission:users.view')
+     ->name('users.index');
+
+ Route::put('admin/users/{user}/roles', [UserManagementController::class, 'updateRoles'])
+     ->middleware('permission:users.update')
+     ->name('users.roles.update');
+
+ Route::get('admin/roles', [RoleManagementController::class, 'index'])
+     ->middleware('permission:roles.view')
+     ->name('roles.index');
+
+ Route::put('admin/roles/{role}', [RoleManagementController::class, 'update'])
+     ->middleware('permission:roles.update')
+     ->name('roles.update');
```

Why:

- once a page needs real data and form submission, it should move from `Route::inertia()` into a controller
- route middleware remains the first authorization barrier
- this keeps the frontend aligned with Laravel named routes and Wayfinder generation

#### 2. `app/Http/Controllers/Admin/RoleManagementController.php`

Before:

- the controller existed only as an empty stub

Before example:

```php
- class RoleManagementController extends Controller
- {
-     //
- }
```

After:

- `index()` now returns the role management Inertia page
- roles are loaded with their permissions and user counts
- permissions are grouped by module prefix like `dashboard`, `users`, and `roles`
- `update()` now syncs permissions for editable roles
- the `Admin` role is intentionally protected as a stable recovery role

Representative diff:

```php
+ 'roles' => Role::query()
+     ->with('permissions')
+     ->withCount('users')
+     ->orderBy('name')
+     ->get()
+     ->map(fn (Role $role): array => [
+         'id' => $role->id,
+         'name' => $role->name,
+         'permissions' => $role->permissions->pluck('name')->values()->all(),
+         'usersCount' => $role->users_count,
+         'editable' => $role->name !== 'Admin',
+     ]),
```

And:

```php
+ public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
+ {
+     if ($role->name === 'Admin') {
+         return to_route('roles.index')->withErrors([
+             'permissions' => 'The Admin role is managed automatically and cannot be edited from this screen.',
+         ]);
+     }
+
+     $role->syncPermissions($request->validated('permissions', []));
+
+     return to_route('roles.index');
+ }
```

Why:

- `syncPermissions()` is the right Spatie method when the screen edits the full permission set for a role
- grouping permissions in the controller keeps the Vue page simpler
- protecting `Admin` avoids a common boilerplate failure mode where the only recovery role gets accidentally restricted

#### 3. `app/Http/Controllers/Admin/UserManagementController.php`

Before:

- this controller was also only an empty stub

After:

- `index()` now returns users with their assigned roles
- available roles are also loaded so the page can render assignment checkboxes
- `updateRoles()` now syncs the selected roles onto a user
- a self-protection rule prevents the current admin from removing their own `Admin` role from this screen

Representative diff:

```php
+ 'users' => User::query()
+     ->with('roles')
+     ->orderBy('name')
+     ->get()
+     ->map(fn (User $user): array => [
+         'id' => $user->id,
+         'name' => $user->name,
+         'email' => $user->email,
+         'roles' => $user->roles->pluck('name')->values()->all(),
+         'isCurrentUser' => request()->user()?->is($user) ?? false,
+     ]),
```

And:

```php
+ $user->syncRoles($roles);
```

Why:

- `syncRoles()` is the correct Spatie method when the screen submits the full current role set
- eager loading `roles` avoids N+1 queries
- protecting the current admin from removing their own admin role keeps the control panel recoverable during setup

#### 4. `app/Http/Requests/Admin/UpdateRoleRequest.php` and `app/Http/Requests/Admin/UpdateUserRolesRequest.php`

Before:

- both requests returned `authorize(): false`
- neither request had validation rules

Before example:

```php
- public function authorize(): bool
- {
-     return false;
- }
```

After:

- each request now checks the relevant permission in `authorize()`
- each request validates an array payload
- each request includes custom validation messages

Representative diff:

```php
+ public function authorize(): bool
+ {
+     return $this->user()?->can('roles.update') ?? false;
+ }
+
+ public function rules(): array
+ {
+     return [
+         'permissions' => ['present', 'array'],
+         'permissions.*' => ['string', 'distinct', 'exists:permissions,name'],
+     ];
+ }
```

Why:

- Form Requests keep controllers thin
- `authorize()` gives Laravel a second authorization layer after route middleware
- validating `present` arrays is important because checkbox-based forms may intentionally submit empty sets

#### 5. `resources/js/components/admin/RolePermissionCard.vue`

Before:

- there was no reusable role editor component
- the roles page only showed an empty placeholder state

After:

- each role is now rendered as a real editable card
- the card uses `useForm()` from Inertia to manage checkbox state
- checkboxes call a `togglePermission()` helper and submit to the generated Wayfinder route

Representative diff:

```ts
+ const form = useForm<{ permissions: string[] }>({
+     permissions: [...props.role.permissions],
+ });
+
+ const togglePermission = (permission: string, checked: boolean | 'indeterminate'): void => {
+     const nextPermissions = new Set(form.permissions);
+     ...
+     form.permissions = [...nextPermissions].sort((left, right) =>
+         left.localeCompare(right),
+     );
+ };
+
+ form.put(updateRole(props.role.id).url, {
+     preserveScroll: true,
+ });
```

Why:

- `useForm()` is the clean Inertia pattern for a server-backed form without building a separate API
- one card per role keeps the page readable as permissions grow
- using the generated route helper avoids hardcoded URLs in Vue

#### 6. `resources/js/components/admin/UserRoleCard.vue`

Before:

- there was no reusable user-role editor component

After:

- each user is now shown with role checkboxes
- the component submits to the generated nested route under `@/routes/users/roles`
- current-session users get an explicit note about the self-protection rule

Representative diff:

```ts
+ import { update } from '@/routes/users/roles';
+
+ const form = useForm<{ roles: string[] }>({
+     roles: [...props.user.roles],
+ });
+
+ form.put(update(props.user.id).url, {
+     preserveScroll: true,
+ });
```

Why:

- the nested route shape makes the intent explicit: this endpoint updates a user's roles, not the full user record
- splitting role assignment into its own component keeps the page logic small and reusable

#### 7. `resources/js/pages/admin/Roles/Index.vue` and `resources/js/pages/admin/Users/Index.vue`

Before:

- both pages were placeholder screens using `EmptyState`

Before example:

```vue
- <EmptyState
-     title="Detailed role management comes next"
-     description="This placeholder proves the route and permission wiring."
- />
```

After:

- each page now receives real props from Laravel
- each page renders the new editor components
- page headers now explain the live RBAC behavior instead of showing placeholder text

After example:

```vue
+ <RolePermissionCard
+     v-for="role in roles"
+     :key="role.id"
+     :role="role"
+     :permission-groups="permissionGroups"
+ />
```

Why:

- Inertia pages should stay page-focused and delegate row/card editing to smaller components
- this keeps the boilerplate maintainable when later CRUD fields are added

#### 8. `resources/js/types/admin.ts` and generated route helpers

Before:

- there were no dedicated frontend types for managed roles, managed users, or grouped permissions
- the generated route files only had the old index routes

After:

- we added explicit admin types for role and user management props
- we regenerated Wayfinder route helpers after changing `routes/web.php`
- new frontend routes now exist for:
  - `roles.update`
  - `users.roles.update`

Representative diff:

```ts
+ export type ManagedRole = {
+     id: number;
+     name: string;
+     permissions: string[];
+     usersCount: number;
+     editable: boolean;
+ };
```

Why:

- strong page prop typing matters once backend data becomes structured
- regenerating Wayfinder after route changes is required so frontend imports remain valid

#### 9. `tests/Feature/Admin/RoleManagementTest.php` and `tests/Feature/Admin/UserManagementTest.php`

Before:

- these tests did not exist

After:

- we added targeted feature tests for:
  - admin can update role permissions
  - admin role cannot be edited from the management screen
  - manager cannot update roles without permission
  - admin can update another user's roles
  - admin cannot remove their own admin role
  - manager cannot update another user's roles

Representative diff:

```php
+ $this->actingAs($admin)
+     ->put(route('roles.update', $managerRole), [
+         'permissions' => [
+             'dashboard.view',
+             'notifications.view',
+             'roles.view',
+         ],
+     ])
+     ->assertRedirect(route('roles.index'));
```

Why:

- this batch changes security-sensitive behavior
- feature tests are the fastest way to prove route middleware, Form Request authorization, controller logic, and Spatie sync methods work together

### Laravel concepts used in this batch

- controller-backed Inertia pages
- Form Requests for validation and authorization
- named routes with route middleware
- Spatie Permission:
  - `syncPermissions()`
  - `syncRoles()`
- eager loading with `with('roles')` and `with('permissions')`
- Wayfinder route generation after backend route changes
- Pest feature tests for authorization and data updates

### What to remember

- use `Route::inertia()` for simple static pages, but move to controllers once a page needs structured data or mutation logic
- use `syncPermissions()` and `syncRoles()` when the submitted form represents the full selected set
- keep at least one protected recovery role so the boilerplate cannot lock itself out during setup
- RBAC is only trustworthy when the same permission system drives routes, controller authorization, sidebar visibility, and tests

#### 6. `resources/js/components/AppLogo.vue`

Before:

- the logo text was hardcoded as `Laravel Starter Kit`
- branding did not reflect the actual app name from Laravel config

Before example:

```vue
- <span class="mb-0.5 truncate leading-tight font-semibold">
-   Laravel Starter Kit
- </span>
```

After:

- we imported `usePage()` from Inertia
- we created a computed `appName`
- the component now reads `page.props.name`, which comes from `HandleInertiaRequests.php`
- the visual style was updated to feel more intentional and less default

After example:

```ts
+ const page = usePage();
+ const appName = computed(() => page.props.name ?? 'Application Boilerplate');
```

```vue
+ <span class="truncate leading-tight font-semibold">
+   {{ appName }}
+ </span>
```

Why:

- the starter should inherit the real app identity
- shared props from Laravel to Inertia are useful for app-level UI like branding

#### 7. `resources/js/components/AppSidebarHeader.vue`

Before:

- the header was functional but visually plain
- it had a transparent/plain background feel

After:

- we added `bg-background/80` and `backdrop-blur`

Representative diff:

```vue
- class="flex h-16 ... border-b ... px-6 ..."
+ class="flex h-16 ... border-b ... bg-background/80 px-6 backdrop-blur ..."
```

Why:

- the shell header should feel like a stable application surface
- this gives better visual separation from page content

#### 8. `resources/js/pages/Dashboard.vue`

Before:

- the dashboard was only placeholder boxes using `PlaceholderPattern`
- it looked like starter scaffolding, not a reusable admin workspace

Before example:

```vue
- <div class="grid auto-rows-min gap-4 md:grid-cols-3">
-   <div class="relative aspect-video ...">
-     <PlaceholderPattern />
-   </div>
- </div>
```

After:

- we removed `PlaceholderPattern`
- we added structured content:
  - hero section
  - starter tracks
  - quick-access cards
  - progress guidance panel
- we linked the dashboard directly to real settings routes

After example:

```ts
+ const quickLinks = [
+   {
+     title: 'Profile settings',
+     href: editProfile(),
+   },
+   {
+     title: 'Security settings',
+     href: editSecurity(),
+   },
+   {
+     title: 'Appearance settings',
+     href: editAppearance(),
+   },
+ ];
```

Why:

- a boilerplate dashboard should communicate structure and next steps
- it should be immediately useful after login
- real links are better than visual placeholders because they prove the shell is integrated

### Laravel and frontend concepts involved

- Inertia page composition
- shared layout patterns
- route helper usage through Wayfinder
- centralized frontend configuration for navigation

### Important files

- `resources/js/navigation/app.ts`
- `resources/js/components/AppSidebar.vue`
- `resources/js/components/NavMain.vue`
- `resources/js/components/NavFooter.vue`
- `resources/js/components/AppLogo.vue`
- `resources/js/components/AppSidebarHeader.vue`
- `resources/js/pages/Dashboard.vue`
- `resources/js/types/navigation.ts`

### Why this approach fits Laravel

Laravel with Inertia works best when page structure is predictable and routing remains the backend source of truth.

By centralizing navigation:

- frontend links stay consistent
- future permission gating becomes easier
- the shell stops scattering navigation definitions across multiple components

By replacing the placeholder dashboard:

- the starter now behaves like a real application shell
- future phases can build on top of a stable layout instead of demo placeholders

### Verification

Programmatic checks run for this batch:

- `npm run types:check`
- `npm run build`
- `php artisan test --compact tests/Feature/Auth/AuthenticationTest.php`

### What to remember

- create one navigation source before adding many modules
- use route helpers instead of hardcoded internal URLs
- finish shell cleanup early so every later module inherits the right layout
- when documenting progress, record file-by-file before/after changes, not just high-level goals

## Entry 003: Phase 1 Shell Foundation Batch 2

### What we did

We completed the second shell batch:

- added a reusable page header component
- added a reusable page container for spacing consistency
- added reusable empty, loading, and page-error state components
- added shell-level quick actions in the top header
- updated the dashboard to use these shared shell primitives

### Code-level change log

#### 1. `resources/js/components/PageHeader.vue`

Before:

- pages created their own top section manually
- title, description, and action placement were not standardized

After:

- we created a shared `PageHeader` component
- it supports:
  - title
  - description
  - optional eyebrow slot
  - optional actions slot

Representative code:

```vue
+ <PageHeader
+   title="Build the reusable admin shell before adding modules."
+   description="This starter now acts like a real project workspace..."
+ >
+   <template #eyebrow>...</template>
+   <template #actions>...</template>
+ </PageHeader>
```

Why:

- page headers are repeated across most admin pages
- standardizing them early prevents each module from inventing a different structure

#### 2. `resources/js/components/PageContainer.vue`

Before:

- page spacing was handled ad hoc inside page components with local `p-4` or similar classes
- that makes spacing drift over time

After:

- we created a shared page container with consistent horizontal and vertical spacing

Representative code:

```vue
+ <div class="flex min-h-full flex-1 flex-col gap-6 px-4 py-4 md:px-6 md:py-6">
+   <slot />
+ </div>
```

Why:

- shell spacing should be inherited, not reinvented on every page

#### 3. `resources/js/components/EmptyState.vue`

Before:

- there was no reusable empty-state component for future CRUD pages

After:

- we created `EmptyState.vue`
- it supports:
  - icon
  - title
  - description
  - optional actions slot

Representative code:

```vue
+ <EmptyState
+   title="Empty states are now reusable"
+   description="Future list and detail pages can use one consistent zero-state pattern..."
+   :icon="SearchX"
+ />
```

Why:

- lists and details often need a zero-state
- this should be one reusable pattern, not many inconsistent placeholders

#### 4. `resources/js/components/LoadingState.vue`

Before:

- there was no reusable shell-level loading pattern

After:

- we created `LoadingState.vue`
- it uses neutral animated blocks as a simple skeleton

Representative code:

```vue
+ <div class="h-4 w-28 animate-pulse rounded-full bg-muted" />
+ <div class="h-8 w-1/3 animate-pulse rounded-full bg-muted" />
```

Why:

- loading feedback should be consistent and reusable
- skeletons help future CRUD pages feel responsive while waiting for data

#### 5. `resources/js/components/PageErrorState.vue`

Before:

- we had `AlertError.vue`, but no page-level wrapper pattern for error sections

After:

- we created `PageErrorState.vue`
- it wraps `AlertError.vue` inside a shell-friendly section container

Representative code:

```vue
+ <section class="rounded-[1.5rem] border border-border/70 bg-card/80 px-6 py-6 shadow-sm">
+   <AlertError :errors="errors" :title="title" />
+ </section>
```

Why:

- field errors and page errors are different
- this gives future pages a standard way to show content-level failures

#### 6. `resources/js/components/AppSidebarHeader.vue`

Before:

- the top shell header only had:
  - sidebar trigger
  - breadcrumbs
- no reusable quick actions were present

Before example:

```vue
- <div class="flex items-center gap-2">
-   <SidebarTrigger class="-ml-1" />
-   <Breadcrumbs ... />
- </div>
```

After:

- we added:
  - appearance toggle button
  - settings shortcut button
- the appearance toggle uses the existing `useAppearance()` composable

After example:

```ts
+ const { resolvedAppearance, updateAppearance } = useAppearance();
+
+ const toggleAppearance = (): void => {
+   updateAppearance(resolvedAppearance.value === 'dark' ? 'light' : 'dark');
+ };
```

```vue
+ <Button ... @click="toggleAppearance">
+   <SunMedium v-if="resolvedAppearance === 'dark'" />
+   <MoonStar v-else />
+ </Button>
+ <Button ... as-child>
+   <Link :href="editProfile()">...</Link>
+ </Button>
```

Why:

- quick actions should be available everywhere in the shell
- a global appearance toggle is useful in a reusable starter
- settings should be easy to reach without relying only on the user dropdown

#### 7. `resources/js/pages/Dashboard.vue`

Before:

- the dashboard already had the first shell rewrite from Batch 1
- but it still owned its own page spacing and top-structure composition

After:

- it now uses:
  - `PageContainer`
  - `PageHeader`
  - `LoadingState`
  - `EmptyState`
  - `PageErrorState`

Representative code:

```vue
+ <PageContainer ...>
+   <PageHeader ... />
+   ...
+   <section class="grid gap-4 xl:grid-cols-3">
+     <LoadingState />
+     <EmptyState ... />
+     <PageErrorState ... />
+   </section>
+ </PageContainer>
```

Why:

- the dashboard is now the first real consumer of the shared shell primitives
- this proves the components are not just theoretical helpers

### Laravel and frontend concepts involved

- component composition in Vue
- Inertia shared shell patterns
- reusable state presentation
- composables for cross-page behavior

### Important files

- `resources/js/components/PageHeader.vue`
- `resources/js/components/PageContainer.vue`
- `resources/js/components/EmptyState.vue`
- `resources/js/components/LoadingState.vue`
- `resources/js/components/PageErrorState.vue`
- `resources/js/components/AppSidebarHeader.vue`
- `resources/js/pages/Dashboard.vue`

### Why this approach fits Laravel

Laravel with Inertia benefits from stable page primitives:

- routes and controllers stay simple
- pages remain thin
- shared components carry the layout and interaction conventions

This is similar to how Laravel encourages shared middleware, Form Requests, and reusable views on the backend: common structure should be centralized.

### Verification

Programmatic checks run for this batch:

- `npm run build`
- `npm run types:check`
- `php artisan test --compact tests/Feature/DashboardTest.php`

### What to remember

- standardize the shell before building many modules
- page spacing should come from shared layout primitives
- loading, empty, and error states are part of the design system, not optional polish
- quick actions belong in the shell, not scattered across individual pages

## Entry 004: Phase 3 RBAC Foundation Batch 1

### What we did

We started the RBAC foundation and made it real in the application:

- installed Spatie Permission
- published the package config and migration
- enabled role support on the `User` model
- added default permissions and roles through a seeder
- protected the dashboard with a real permission
- exposed roles, permissions, and helper booleans to Inertia
- made the sidebar permission-aware
- made the database seeder safe to rerun while iterating

### Code-level change log

#### 1. `composer.json` and `composer.lock`

Before:

- the project did not include a dedicated RBAC package

After:

- we added:

```json
+ "spatie/laravel-permission": "^7.0"
```

Installed version:

- `spatie/laravel-permission` `7.2.3`

Why:

- Laravel has gates and policies built in, but a reusable boilerplate needs first-class role and permission tables plus assignment helpers
- Spatie Permission is the standard package for this job in Laravel apps

#### 2. `config/permission.php` and `database/migrations/2026_03_16_082039_create_permission_tables.php`

Before:

- no permission package config
- no roles/permissions tables in the database

After:

- we published the package assets
- Laravel now has the package config file
- the project now includes the migration for:
  - roles
  - permissions
  - model_has_roles
  - model_has_permissions
  - role_has_permissions

Why:

- roles and permissions need database storage before any UI or policy work can happen

#### 3. `bootstrap/app.php`

Before:

- Laravel 12 middleware setup existed, but there were no aliases for Spatie permission middleware

Before example:

```php
- $middleware->web(append: [
-     HandleAppearance::class,
-     HandleInertiaRequests::class,
-     AddLinkHeadersForPreloadedAssets::class,
- ]);
```

After:

- we registered middleware aliases for:
  - `role`
  - `permission`
  - `role_or_permission`

After example:

```php
+ $middleware->alias([
+     'role' => RoleMiddleware::class,
+     'permission' => PermissionMiddleware::class,
+     'role_or_permission' => RoleOrPermissionMiddleware::class,
+ ]);
```

Why:

- in Laravel 12, middleware aliases are registered in `bootstrap/app.php`
- route-level RBAC checks need these aliases

#### 4. `app/Models/User.php`

Before:

- `User` had auth and 2FA support, but no role/permission behavior

Before example:

```php
- use HasFactory, Notifiable, TwoFactorAuthenticatable;
```

After:

- we added the Spatie trait:

```php
+ use HasFactory, HasRoles, Notifiable, TwoFactorAuthenticatable;
```

Why:

- `HasRoles` adds methods like:
  - `assignRole()`
  - `hasRole()`
  - `givePermissionTo()`
  - `can()`

This is the bridge between the user model and RBAC behavior.

#### 5. `app/Providers/AppServiceProvider.php`

Before:

- there was no global Admin override for authorization

After:

- we added a `Gate::before(...)` rule:

```php
+ Gate::before(fn (User $user, string $ability): ?bool => $user->hasRole('Admin') ? true : null);
```

Why:

- the Admin role should act as a superuser in the boilerplate
- this keeps future policy code simpler because Admin does not need to be repeated in every policy rule

#### 6. `database/seeders/RolePermissionSeeder.php`

Before:

- the new seeder file was empty

After:

- we added default permission creation
- we added default roles:
  - `Admin`
  - `Manager`
  - `Member`
  - `ReadOnly`
- we assigned permissions to each role
- we reset the package permission cache before and after

Representative code:

```php
+ $permissions = [
+     'dashboard.view',
+     'users.view',
+     'users.create',
+     'users.update',
+     'users.delete',
+     'roles.view',
+     'roles.create',
+     'roles.update',
+     'roles.delete',
+     'notifications.view',
+     'activity-logs.view',
+ ];
```

Why:

- a reusable starter should ship with a default access matrix
- this gives us a base to expand later when Users, Roles, Notifications, and Activity Logs modules are built

#### 7. `database/seeders/DatabaseSeeder.php`

Before:

- it created `test@example.com` directly with `User::factory()->create(...)`
- rerunning `php artisan db:seed` could fail on duplicate email
- the seeded user did not get a role

Before example:

```php
- User::factory()->create([
-     'name' => 'Test User',
-     'email' => 'test@example.com',
- ]);
```

After:

- we call `RolePermissionSeeder`
- we changed user creation to `updateOrCreate(...)`
- we assign the `Admin` role to the seeded user

After example:

```php
+ $this->call(RolePermissionSeeder::class);
+
+ $user = User::query()->updateOrCreate([
+     'email' => 'test@example.com',
+ ], [
+     'name' => 'Test User',
+ ]);
+
+ $user->assignRole('Admin');
```

Why:

- seeders should be safe to rerun during development
- the default login should land in an authorized account for local testing

#### 8. `app/Http/Middleware/HandleInertiaRequests.php`

Before:

- Inertia only received:
  - app name
  - authenticated user
  - sidebar open state

Before example:

```php
- 'auth' => [
-     'user' => $request->user(),
- ],
```

After:

- Inertia now also receives:
  - role names
  - permission names
  - helper `can` booleans

After example:

```php
+ 'auth' => [
+     'user' => $user,
+     'roles' => $user?->getRoleNames()->values()->all() ?? [],
+     'permissions' => $user?->getAllPermissions()->pluck('name')->values()->all() ?? [],
+     'can' => [
+         'viewDashboard' => $user?->can('dashboard.view') ?? false,
+         'manageUsers' => $user?->can('users.view') ?? false,
+         'manageRoles' => $user?->can('roles.view') ?? false,
+     ],
+ ],
```

Why:

- Vue components need permission-aware data without making extra API calls
- this is the Inertia way of exposing authenticated app context

#### 9. `routes/web.php`

Before:

- the dashboard route only required:
  - `auth`
  - `verified`

Before example:

```php
- Route::middleware(['auth', 'verified'])->group(function () {
-     Route::inertia('dashboard', 'Dashboard')->name('dashboard');
- });
```

After:

- the dashboard route also requires `permission:dashboard.view`

After example:

```php
+ Route::middleware(['auth', 'verified', 'permission:dashboard.view'])->group(function () {
+     Route::inertia('dashboard', 'Dashboard')->name('dashboard');
+ });
```

Why:

- RBAC should protect real routes early
- the dashboard became the first proof-of-concept protected page

#### 10. `resources/js/types/auth.ts`

Before:

- frontend auth typing only expected:
  - `user`

After:

- auth typing now includes:
  - `user`
  - `roles`
  - `permissions`
  - `can`

Why:

- once the backend shares more auth context, the frontend types should reflect that shape exactly

#### 11. `resources/js/types/navigation.ts`

Before:

- `NavItem` had no permission metadata

After:

- we added:

```ts
+ permission?: string;
```

Why:

- navigation items need optional permission requirements so the shell can filter them

#### 12. `resources/js/navigation/app.ts`

Before:

- navigation items had no permission requirement

After:

- the dashboard item is now permission-aware:

```ts
+ {
+   title: 'Dashboard',
+   href: dashboard(),
+   icon: LayoutGrid,
+   permission: 'dashboard.view',
+ }
```

Why:

- the first navigation item should already reflect the RBAC model

#### 13. `resources/js/components/AppSidebar.vue`

Before:

- navigation rendered directly from the config without permission filtering

After:

- we read auth data from Inertia
- we filter navigation items by permission
- we remove empty groups after filtering

Representative code:

```ts
+ const mainNavigation = computed<NavGroup[]>(() =>
+     appNavigation
+         .map((group) => ({
+             ...group,
+             items: group.items.filter((item) => {
+                 if (!item.permission) {
+                     return true;
+                 }
+
+                 return auth.value.permissions.includes(item.permission);
+             }),
+         }))
+         .filter((group) => group.items.length > 0),
+ );
```

Why:

- route protection alone is not enough
- the sidebar should also hide links the user cannot use

#### 14. `tests/Feature/DashboardTest.php`

Before:

- tests only proved:
  - guests are redirected
  - any authenticated user can visit the dashboard

After:

- we changed the test to match RBAC reality
- we added:
  - forbidden for authenticated users without permission
  - success for authenticated users with permission

Representative code:

```php
+ test('authenticated users without dashboard permission are forbidden', function () {
+     Permission::findOrCreate('dashboard.view', 'web');
+     $user = User::factory()->create();
+     $this->actingAs($user);
+     $this->get(route('dashboard'))->assertForbidden();
+ });
```

Why:

- once a route is permission-protected, tests must verify both allow and deny behavior

### Laravel concepts involved

- package installation and publishing
- middleware aliasing in Laravel 12
- role and permission assignment
- seeders
- Inertia shared props
- route middleware authorization
- feature tests with Pest

### Important files

- `bootstrap/app.php`
- `app/Models/User.php`
- `app/Providers/AppServiceProvider.php`
- `app/Http/Middleware/HandleInertiaRequests.php`
- `routes/web.php`
- `database/seeders/RolePermissionSeeder.php`
- `database/seeders/DatabaseSeeder.php`
- `resources/js/components/AppSidebar.vue`
- `resources/js/navigation/app.ts`
- `tests/Feature/DashboardTest.php`

### Why this approach fits Laravel

This is the Laravel way because:

- package config and migrations were published instead of being hidden custom code
- middleware aliases were registered in `bootstrap/app.php`, which is the Laravel 12 pattern
- authorization is enforced at the route level first
- frontend permission awareness is provided through shared Inertia props
- seeded defaults make local development reproducible

### Verification

Programmatic checks run for this batch:

- `php artisan migrate --no-interaction`
- `php artisan db:seed --no-interaction`
- `php artisan test --compact tests/Feature/DashboardTest.php`
- `npm run types:check`
- `npm run build`
- `vendor/bin/pint --dirty --format agent`

### What to remember

- install the RBAC package before building admin modules
- protect at least one real route early so the RBAC layer is proven
- make seeders idempotent during development
- route protection and sidebar visibility should move together

## Entry 005: Phase 3 RBAC Foundation Batch 2

### What we did

We added the first visible role-specific experience:

- seeded dedicated demo credentials for each role
- added an `Administration` sidebar group that only appears when permissions allow it
- added admin-only placeholder pages for `Users` and `Roles`
- added route protection for those pages
- added tests proving role-based route differences
- added a sidebar access summary so the current role is visible in the UI

### Role demo credentials

The seeder now creates these reusable local accounts with password `password`:

- `admin@example.com` -> `Admin`
- `manager@example.com` -> `Manager`
- `member@example.com` -> `Member`
- `readonly@example.com` -> `ReadOnly`
- `external@example.com` -> `External`
- `test@example.com` -> `Admin`

### Code-level change log

#### 1. `database/seeders/DatabaseSeeder.php`

Before:

- only one local account was created
- there was no easy way to log in as different roles and compare access

Before example:

```php
- $user = User::query()->updateOrCreate([
-     'email' => 'test@example.com',
- ], [
-     'name' => 'Test User',
- ]);
-
- $user->assignRole('Admin');
```

After:

- we now seed multiple dedicated accounts
- each account is assigned a role
- each seeded account gets password `password`
- we use `syncRoles()` so reruns stay consistent

After example:

```php
+ $accounts = [
+     ['name' => 'Admin User', 'email' => 'admin@example.com', 'role' => 'Admin'],
+     ['name' => 'Manager User', 'email' => 'manager@example.com', 'role' => 'Manager'],
+     ['name' => 'Member User', 'email' => 'member@example.com', 'role' => 'Member'],
+     ['name' => 'Read Only User', 'email' => 'readonly@example.com', 'role' => 'ReadOnly'],
+     ['name' => 'External User', 'email' => 'external@example.com', 'role' => 'External'],
+ ];
```

Why:

- role testing becomes immediate
- you can now log in as each role and verify the sidebar and routes directly

#### 2. `database/seeders/RolePermissionSeeder.php`

Before:

- the default roles ended at:
  - `Admin`
  - `Manager`
  - `Member`
  - `ReadOnly`

After:

- we added `External`

After example:

```php
+ $external = Role::findOrCreate('External', 'web');
+ $external->syncPermissions([
+     'dashboard.view',
+ ]);
```

Why:

- you asked for a guest-like restricted role
- to avoid confusion with Laravel's unauthenticated `guest`, we implemented a signed-in restricted role called `External`

#### 3. `resources/js/navigation/app.ts`

Before:

- the sidebar only had:
  - `Workspace`
  - `Account`
- there was no admin-only navigation group

After:

- we added `Administration`
- that group includes:
  - `Users`
  - `Roles`
- both items carry permission requirements

After example:

```ts
+ {
+   title: 'Administration',
+   description: 'Visible only when the signed-in user can manage access',
+   items: [
+     {
+       title: 'Users',
+       href: usersIndex(),
+       icon: Users,
+       permission: 'users.view',
+     },
+     {
+       title: 'Roles',
+       href: rolesIndex(),
+       icon: Shield,
+       permission: 'roles.view',
+     },
+   ],
+ }
```

Why:

- this creates the first real example of:
  - common sidebar areas for everyone
  - role-specific or permission-specific sidebar areas for privileged users

#### 4. `resources/js/types/navigation.ts`

Before:

- `NavGroup` only had:
  - `title`
  - `items`

After:

- we added:

```ts
+ description?: string;
```

Why:

- grouped sidebars are easier to understand when each section can explain why it exists

#### 5. `resources/js/components/NavMain.vue`

Before:

- group labels rendered, but there was no descriptive text below them

After:

- each group can now display a short description

After example:

```vue
+ <p
+   v-if="group.description"
+   class="px-2 pb-2 text-xs leading-5 text-muted-foreground"
+ >
+   {{ group.description }}
+ </p>
```

Why:

- this helps distinguish:
  - common/shared groups
  - admin-only groups

#### 6. `resources/js/components/AppSidebar.vue`

Before:

- the sidebar filtered items by permission, but there was no visible role summary

After:

- we added `roleSummary`
- we added a small access panel in the sidebar header

After example:

```ts
+ const roleSummary = computed(() => {
+     if (auth.value.roles.length === 0) {
+         return 'No role assigned';
+     }
+
+     return auth.value.roles.join(', ');
+ });
```

```vue
+ <div class="mt-4 rounded-2xl border border-sidebar-border/70 bg-background/80 px-3 py-3 text-xs leading-5 text-muted-foreground">
+   <div class="font-medium text-foreground">Current access</div>
+   <div class="mt-1">{{ roleSummary }}</div>
+ </div>
```

Why:

- role-aware apps should make the current access context visible
- this helps manual verification while developing the boilerplate

#### 7. `routes/web.php`

Before:

- only the dashboard was permission-protected in the browser routes

After:

- we added two admin-only pages:
  - `/admin/users`
  - `/admin/roles`
- each uses permission middleware

After example:

```php
+ Route::inertia('admin/users', 'admin/Users/Index')
+     ->middleware('permission:users.view')
+     ->name('users.index');
+
+ Route::inertia('admin/roles', 'admin/Roles/Index')
+     ->middleware('permission:roles.view')
+     ->name('roles.index');
```

Why:

- role-specific sidebars are only meaningful when the routes behind them are also protected

#### 8. `resources/js/pages/admin/Users/Index.vue`

Before:

- no admin users page existed

After:

- we added an admin-only placeholder page for future user management

Why:

- this is the first visible proof that the Admin role gets access to pages other roles do not
- later this page will become the real Users CRUD module

#### 9. `resources/js/pages/admin/Roles/Index.vue`

Before:

- no roles management page existed

After:

- we added an admin-only placeholder page for future detailed role and permission management

Why:

- this is the correct future home for the checkbox-based role permission editor you described

#### 10. `resources/js/routes/users/index.ts` and `resources/js/routes/roles/index.ts`

Before:

- there were no frontend helpers for the new admin routes

After:

- we added simple route helpers for:
  - `/admin/users`
  - `/admin/roles`

Why:

- frontend navigation should not hardcode internal app URLs more than necessary

#### 11. `tests/Feature/RoleAccessTest.php`

Before:

- the generated test file was empty

After:

- we added role-based access tests:
  - Admin can access Users and Roles pages
  - Manager cannot access Users or Roles pages
  - ReadOnly can access Dashboard but not Users or Roles pages

After example:

```php
+ test('manager cannot access users or roles pages', function () {
+     $this->seed(RolePermissionSeeder::class);
+     $user = User::factory()->create();
+     $user->assignRole('Manager');
+     $this->actingAs($user);
+
+     $this->get(route('users.index'))->assertForbidden();
+     $this->get(route('roles.index'))->assertForbidden();
+ });
```

Why:

- RBAC is only trustworthy when the differences between roles are tested explicitly

### Laravel concepts involved

- seeders for reusable local credentials
- permission-protected Inertia routes
- permission-aware navigation
- feature tests for role access behavior

### Important files

- `database/seeders/DatabaseSeeder.php`
- `database/seeders/RolePermissionSeeder.php`
- `resources/js/navigation/app.ts`
- `resources/js/components/AppSidebar.vue`
- `resources/js/components/NavMain.vue`
- `routes/web.php`
- `resources/js/pages/admin/Users/Index.vue`
- `resources/js/pages/admin/Roles/Index.vue`
- `tests/Feature/RoleAccessTest.php`

### Why this approach fits Laravel

Laravel apps are easiest to reason about when authorization is layered consistently:

- seeders provide predictable demo accounts
- routes provide backend enforcement
- Inertia pages provide the frontend experience
- sidebar visibility reflects the same permission system as the routes

This prevents the common problem you mentioned: all users seeing all sidebar items even when they cannot actually use them.

### Verification

Programmatic checks run for this batch:

- `php artisan db:seed --no-interaction`
- `php artisan test --compact tests/Feature/RoleAccessTest.php tests/Feature/DashboardTest.php`
- `npm run types:check`
- `npm run build`
- `vendor/bin/pint --dirty --format agent`

### What to remember

- role-specific credentials are extremely useful during development
- sidebar visibility and route protection should always agree
- admin-only placeholder pages are a good step before building full CRUD modules
- the future checkbox-style role editor belongs in the Roles management module
