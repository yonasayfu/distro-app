# Boilerplate Integration Guidance

This file explains how to take the current boilerplate and integrate a new module, entity, page, and related permissions into it without breaking the existing structure.

Use this file when turning the starter into a real product.

## 1. Is The Boilerplate Already Enough?

For your stated goal, this boilerplate is already strong enough for most upcoming internal or admin-style Laravel apps.

What is already ready:

- Auth and account flows
- Fortify + 2FA
- Neutral app shell
- RBAC with seeded roles and dynamic sidebar gating
- Users and roles management
- Shared CRUD patterns
- Notifications and activity logs
- API baseline
- Export/print foundation
- Global search
- Setup and CI baseline

What you still add per new app:

- your real domain entities
- your real business rules
- app-specific permissions
- app-specific API endpoints
- app-specific dashboards and reports

So the right mindset is:

- do not rebuild the platform layer
- only add your domain modules on top of it

## 2. Recommended Integration Order

When starting a new app from this boilerplate, add features in this order:

1. Define the module and entity names
2. Add database tables and models
3. Add permissions for that module
4. Seed those permissions into the RBAC system
5. Add routes and controllers
6. Add Inertia pages
7. Add sidebar or search integration if needed
8. Add notifications or activity logging if needed
9. Add API endpoints if the module must be consumed outside the web UI
10. Add tests

Do not start with the frontend first. The stable order is:

- database
- permissions
- backend
- frontend
- tests

## 3. Core Boilerplate Rules For Every New Module

Every new module should follow these rules:

- add a permission for each meaningful page or action
- gate routes with permission middleware
- gate sidebar items with the same permission
- gate page actions with the same permission
- use Form Requests for validation
- use Inertia pages under `resources/js/pages`
- use shared page patterns already in the repo
- add activity logging for important create, update, delete, approval, or export actions
- add tests for allowed and forbidden access

Do not hardcode visibility in Vue without backend permission enforcement.

## 4. Example: Add A New Module

Example module:

- entity: `Project`
- pages:
  - Projects index
  - Project create
  - Project edit
- permissions:
  - `projects.view`
  - `projects.create`
  - `projects.update`
  - `projects.delete`

## 5. Step-By-Step Example

### Step 1: Create the model and migration

Use Laravel conventions:

```bash
php artisan make:model Project -mf
```

That gives you:

- model
- migration
- factory

Example table fields:

- `name`
- `code`
- `description`
- timestamps

## Step 2: Add the permissions

Update the permission seeder:

- [RolePermissionSeeder.php](/Users/yonassayfu/Herd/distro-app/database/seeders/RolePermissionSeeder.php)

Add:

```php
'projects.view',
'projects.create',
'projects.update',
'projects.delete',
```

Then decide which default roles should receive them.

Example:

- `Admin`: all
- `Manager`: `projects.view`, `projects.create`, `projects.update`
- `Member`: `projects.view`

## Step 3: Add permission descriptions for the role editor

Update:

- [RoleManagementController.php](/Users/yonassayfu/Herd/distro-app/app/Http/Controllers/Admin/RoleManagementController.php)

Inside `permissionDescription()` add entries like:

```php
'projects.view' => 'Shows the projects module and allows access to the projects index page.',
'projects.create' => 'Allows creating new projects.',
'projects.update' => 'Allows editing project details.',
'projects.delete' => 'Allows deleting projects.',
```

This is important because the role-management UI depends on these descriptions.

## Step 4: Create requests and controller

Use Laravel structure:

```bash
php artisan make:controller Admin/ProjectManagementController --no-interaction
php artisan make:request Admin/StoreProjectRequest --no-interaction
php artisan make:request Admin/UpdateProjectRequest --no-interaction
```

Then follow the same pattern already used in:

- [UserManagementController.php](/Users/yonassayfu/Herd/distro-app/app/Http/Controllers/Admin/UserManagementController.php)
- [RoleManagementController.php](/Users/yonassayfu/Herd/distro-app/app/Http/Controllers/Admin/RoleManagementController.php)

## Step 5: Add routes with permission middleware

Update:

- [web.php](/Users/yonassayfu/Herd/distro-app/routes/web.php)

Example:

```php
Route::get('admin/projects', [ProjectManagementController::class, 'index'])
    ->middleware('permission:projects.view')
    ->name('projects.index');

Route::get('admin/projects/create', [ProjectManagementController::class, 'create'])
    ->middleware('permission:projects.create')
    ->name('projects.create');

Route::post('admin/projects', [ProjectManagementController::class, 'store'])
    ->middleware('permission:projects.create')
    ->name('projects.store');

Route::get('admin/projects/{project}/edit', [ProjectManagementController::class, 'edit'])
    ->middleware('permission:projects.update')
    ->name('projects.edit');

Route::put('admin/projects/{project}', [ProjectManagementController::class, 'update'])
    ->middleware('permission:projects.update')
    ->name('projects.update');

Route::delete('admin/projects/{project}', [ProjectManagementController::class, 'destroy'])
    ->middleware('permission:projects.delete')
    ->name('projects.destroy');
```

## Step 6: Add the sidebar item

Update:

- [app.ts](/Users/yonassayfu/Herd/distro-app/resources/js/navigation/app.ts)

Example:

```ts
{
    title: 'Projects',
    href: projectsIndex(),
    icon: FolderKanban,
    permission: 'projects.view',
}
```

Important:

- the sidebar item must use the same permission as the index route

## Step 7: Add the pages

Add pages under:

- `resources/js/pages/admin/Projects/Index.vue`
- `resources/js/pages/admin/Projects/Create.vue`
- `resources/js/pages/admin/Projects/Edit.vue`

Follow the same page style as:

- [admin/Users/Index.vue](/Users/yonassayfu/Herd/distro-app/resources/js/pages/admin/Users/Index.vue)
- [admin/Users/Create.vue](/Users/yonassayfu/Herd/distro-app/resources/js/pages/admin/Users/Create.vue)
- [admin/Users/Edit.vue](/Users/yonassayfu/Herd/distro-app/resources/js/pages/admin/Users/Edit.vue)

Use existing reusable pieces:

- `PageContainer`
- `PageHeader`
- `ResourceToolbar`
- `ResourceTable`
- `ResourcePagination`
- `ConfirmActionDialog`

## Step 8: Add activity logging

For important actions, record events using:

- [ActivityLogger.php](/Users/yonassayfu/Herd/distro-app/app/Support/ActivityLogger.php)

Example:

```php
ActivityLogger::record(
    actor: $request->user(),
    event: 'projects.created',
    description: "Created project {$project->name}.",
    subject: $project,
    request: $request,
);
```

Recommended event names:

- `projects.created`
- `projects.updated`
- `projects.deleted`
- `projects.exported`

## Step 9: Add notifications if the module needs them

Use:

- [SystemMessageNotification.php](/Users/yonassayfu/Herd/distro-app/app/Notifications/SystemMessageNotification.php)

Example:

```php
$user->notify(new SystemMessageNotification(
    title: 'Project assigned',
    message: 'You were assigned to a project.',
    actionUrl: '/admin/projects/5/edit',
    actionLabel: 'Open project',
));
```

This automatically fits the existing bell, notifications page, and notification API.

## Step 10: Add global search support

If the module should appear in search, update:

- [GlobalSearchController.php](/Users/yonassayfu/Herd/distro-app/app/Http/Controllers/GlobalSearchController.php)

Add a grouped search method similar to the existing ones.

Rule:

- only return module results if the current user has that module's `view` permission

## Step 11: Add export support if needed

If the module should export data:

1. add the relevant permission, for example:
   - `projects.export`
2. add an export action to:
   - [ExportCenterController.php](/Users/yonassayfu/Herd/distro-app/app/Http/Controllers/ExportCenterController.php)
3. log the export event
4. add tests

Do not add random download routes without tying them into permissions and activity logging.

## Step 12: Add API support if needed

If the module needs mobile/API usage:

1. create an API controller in `app/Http/Controllers/Api/V1`
2. create an API resource in `app/Http/Resources/Api/V1`
3. add routes in:
   - [api.php](/Users/yonassayfu/Herd/distro-app/routes/api.php)
4. follow the shared pagination pattern through:
   - [ApiPagination.php](/Users/yonassayfu/Herd/distro-app/app/Support/ApiPagination.php)

## Step 13: Add tests

Minimum tests for a real module:

- authorized user can access index
- unauthorized user is forbidden
- create works
- update works
- delete works
- sidebar/search behavior is correct if the feature appears there

Use the existing test style from:

- [UserCrudTest.php](/Users/yonassayfu/Herd/distro-app/tests/Feature/Admin/UserCrudTest.php)
- [RoleCrudTest.php](/Users/yonassayfu/Herd/distro-app/tests/Feature/Admin/RoleCrudTest.php)
- [GlobalSearchTest.php](/Users/yonassayfu/Herd/distro-app/tests/Feature/Feature/GlobalSearchTest.php)
- [ExportFoundationTest.php](/Users/yonassayfu/Herd/distro-app/tests/Feature/Feature/ExportFoundationTest.php)

## 6. Short Integration Checklist

For every new module, check all of these:

- migration added
- model added
- factory added
- permissions added
- role seeder updated
- permission descriptions updated
- routes added
- controller added
- Form Requests added
- Inertia pages added
- sidebar item added if needed
- search integration added if needed
- export integration added if needed
- activity logging added
- API added if needed
- tests added

## 7. What Makes This Boilerplate Easy To Reuse

This boilerplate is easy to reuse because the hard parts are already solved:

- auth is already stable
- RBAC already exists
- sidebar is already permission-driven
- CRUD patterns already exist
- notifications and logs already exist
- API baseline already exists

That means a new app mostly becomes:

- new tables
- new permissions
- new controllers
- new pages

not a rebuild of the platform.

## 8. Final Guidance

When starting a new app from this boilerplate:

- do not change the platform first
- add one small domain module correctly
- use that module as the pattern for the rest

The right first downstream module should usually be something simple like:

- projects
- clients
- products
- categories

not a huge workflow-heavy module.
