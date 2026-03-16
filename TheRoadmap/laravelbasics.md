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
