# Boilerplate Roadmap

## Objective

Turn the current repo from a Laravel Vue starter kit into a clean, reusable, domain-neutral boilerplate that can be used as the base for future projects.

This is not a plan to rebuild the asset-management app. It is a plan to extract and rebuild only the reusable platform layer that appeared repeatedly across the reference docs.

## Current Baseline

The current codebase already gives us a good starting point:

- Laravel 12
- Inertia + Vue 3
- Fortify auth flows
- Two-factor authentication
- Profile, security, and appearance settings
- Basic dashboard route
- Basic sidebar shell
- Wayfinder route generation

What is still missing for a real boilerplate:

- RBAC
- Admin user and role management
- Shared CRUD patterns
- Notification center
- Activity log UI
- API baseline
- Setup and operations documentation
- Stronger app shell and reusable navigation structure

## Scope Rules

### Keep in Boilerplate V1

- `users` as the core identity model
- Authentication and security flows
- Role and permission system
- Neutral admin shell
- Shared tables, filters, forms, and feedback patterns
- Notifications
- Activity logging
- API foundation
- CI and setup docs

### Exclude from Boilerplate V1

- Asset-specific schemas and workflows
- Staff as a separate domain model
- Procurement, vendors, software, contracts, and freshservice-style features
- Audit workflows
- Maintenance and warranty modules
- Asset-specific reporting and import/export flows
- Messaging and task modules
- Mailpit mailbox ingestion

### Design Decision

The boilerplate should remain `users`-centric unless you explicitly want an employee-centric boilerplate. A separate `staff` model is useful in some products, but it should not be part of the base starter unless the starter is intentionally HR or operations focused.

## Target Boilerplate V1

The finished V1 should provide:

- A polished app shell
- Auth, 2FA, profile, appearance, and security settings
- RBAC with policies and sidebar gating
- Users, roles, and permission management
- Shared CRUD conventions for future modules
- Notification center and unread badge
- Activity logging and admin audit page
- Base API under `/api/v1`
- Setup docs for local development, mail, queues, scheduler, tests, and deployment

## Phase Plan

### Phase 0: Scope Freeze and Extraction

Goal:
Decide the exact scope of the boilerplate and separate reusable infrastructure from project-specific modules.

Tasks:

- Freeze V1 scope and explicitly mark what is out of scope.
- Adopt `users` as the base identity model.
- Keep Laravel default structure unless a strong reason appears to change it.
- Consolidate the useful planning, UI, and architecture notes into `TheRoadmap/`.

Deliverables:

- This roadmap
- Detailed task checklist
- Reusable template notes
- Reference carryover mapping

Acceptance:

- The project has a documented V1 boundary.
- There is no ambiguity between boilerplate scope and downstream app scope.

### Phase 1: App Shell and Design Foundation

Goal:
Replace the demo look and navigation with a reusable admin shell.

Tasks:

- Replace starter-style welcome and dashboard placeholders with a neutral admin-ready shell.
- Centralize sidebar and header configuration in a single source.
- Standardize breadcrumbs, page wrappers, empty states, loading states, and error surfaces.
- Standardize quick actions in the header.
- Keep the shell neutral enough to support many future projects.

Deliverables:

- Config-driven navigation
- Reusable page shell
- Neutral dashboard starter page
- Shared layout conventions

Acceptance:

- The app no longer feels like the default Laravel starter.
- New pages can be added without inventing layout and toolbar structure each time.

### Phase 2: Auth, Account, and Security Foundation

Goal:
Keep the current Fortify base and turn it into a polished default authentication layer.

Tasks:

- Confirm login, register, password reset, email verification, and 2FA flows.
- Keep profile, security, and appearance settings.
- Improve UX and consistency of these pages.
- Ensure rate limiting and recovery flows remain safe and predictable.
- Add tests for critical auth flows.

Deliverables:

- Production-ready auth and account settings layer
- Auth tests

Acceptance:

- A new project can use auth immediately without further scaffolding.

### Phase 3: RBAC Foundation

Goal:
Add the access-control layer that makes the starter useful for real internal applications.

Tasks:

- Install and configure Spatie Permission.
- Define a small default role matrix.
- Seed permissions and roles.
- Add policy examples and route middleware conventions.
- Gate sidebar items, pages, actions, and API endpoints.
- Add tests for role and permission enforcement.

Deliverables:

- Roles and permissions tables
- Seeders
- Policy conventions
- Permission-aware navigation

Acceptance:

- Unauthorized users cannot see or use restricted features.
- Future modules can follow the same authorization pattern.

### Phase 4: RBAC Management and Role Experience

Goal:
Turn RBAC from a backend-only setup into a fully manageable admin feature with clear role-specific experiences.

Tasks:

- Seed dedicated demo users for each default role so access differences can be tested immediately.
- Add admin-only role management pages.
- Add detailed permission assignment so Admin can control what each role can access.
- Ensure sidebar items, pages, page actions, CRUD actions, print, export, and future module actions all read from permission checks.
- Ensure newly created users inherit access dynamically based on assigned roles instead of hardcoded UI conditions.
- Define a repeatable rule for future modules: every new page or action must register a permission and surface it in role management.

Deliverables:

- Dedicated role demo credentials
- Admin-only role management flow
- Detailed permission matrix UI
- Permission-driven sidebar and action visibility

Acceptance:

- Each role can be tested with its own account.
- Admin can control access without editing code for normal role changes.
- Non-admin users only see and access what belongs to their assigned role or direct permissions.

### Phase 5: Admin Core Modules

Goal:
Ship the first reusable modules that every admin-oriented starter usually needs.

Tasks:

- Build Users module.
- Build Roles module.
- Build Permissions or permission-assignment flow.
- Use Form Requests, policies, pagination, filters, and flash feedback.
- Treat these modules as the canonical example for all future modules.

Deliverables:

- Users CRUD
- Roles CRUD
- Role assignment UI

Acceptance:

- The boilerplate can manage its own users and access model.

### Phase 5.5: Early API Foundation

Goal:
Establish the first reusable API contract early so RBAC is shared across web and non-web clients instead of being bolted on later.

Tasks:

- Install Sanctum and the API scaffold.
- Create `/api/v1` route structure.
- Add token login, logout, and current-user endpoints.
- Return roles and permissions in the authenticated user payload.
- Add one permission-protected admin example endpoint.
- Standardize unauthenticated and forbidden API responses.
- Add focused Pest API tests.

Deliverables:

- Sanctum token auth
- Versioned API routes
- Current-user RBAC payload
- One protected admin API example

Acceptance:

- Mobile or third-party clients can authenticate with tokens.
- The same permission model now protects both web and API entry points.
- The API baseline exists early without forcing a full API expansion yet.

### Phase 6: Shared CRUD Foundation

Goal:
Build the reusable UI and interaction layer for future modules.

Tasks:

- Create a shared toolbar pattern for index pages.
- Create a shared filter and search pattern.
- Standardize table actions using icons with accessible labels.
- Standardize pagination placement and behavior.
- Standardize form pages and validation display.
- Standardize confirmation dialogs and toast feedback.
- Standardize empty states and skeleton loading.

Deliverables:

- Shared toolbar pattern
- Shared list pattern
- Shared form pattern
- Feedback and confirmation pattern

Acceptance:

- A future module can be added by following one stable CRUD template.

### Phase 7: Notifications and Activity Logs

Goal:
Add cross-cutting features that make the starter operationally useful.

Tasks:

- Build notification bell and notifications page.
- Add mark-read and mark-all-read actions.
- Add database notification usage pattern.
- Add activity logging for auth, admin, and settings changes.
- Build admin audit log UI with filters and detail view.

Deliverables:

- Notification center
- Activity log system
- Audit log admin screen

Acceptance:

- Users can receive in-app notifications.
- Admins can inspect key actions across the system.

### Phase 8: API Baseline

Goal:
Prepare the boilerplate for mobile or third-party integrations.

Tasks:

- Add Sanctum.
- Add `/api/v1` routing structure.
- Create auth, profile, and notification endpoints.
- Standardize pagination and error envelope.
- Add OpenAPI or Postman artifacts.
- Add API tests with Pest.

Deliverables:

- Base API
- API docs
- API smoke tests

Acceptance:

- A future project can extend the API without redesigning auth or response structure.

### Phase 9: Developer Experience and Operations

Goal:
Make the boilerplate easy to start, validate, and deploy.

Tasks:

- Write root README and setup guide.
- Add operations guide for mail, queues, scheduler, cache, and deployment.
- Add CI workflow for lint, tests, types, and build.
- Add devcontainer or docker setup if desired.
- Review `.env.example` for completeness.

Deliverables:

- Updated docs
- CI configuration
- Local setup guidance

Acceptance:

- A fresh clone has a predictable setup path.
- Quality checks are documented and automated.

### Phase 10: Optional Extensions

Goal:
Keep future upgrades organized without polluting V1.

Candidates:

- Impersonation
- Print and export foundation
- Global search
- Localization scaffold
- Observability baseline
- Feature toggles
- Messaging and tasks
- Mailpit mailbox ingestion

Acceptance:

- Optional features remain modular and do not distort the V1 starter scope.

## Recommended Build Order

Build in this order:

1. Phase 0
2. Phase 1
3. Phase 2
4. Phase 3
5. Phase 4
6. Phase 5
7. Phase 6
8. Phase 7
9. Phase 8
10. Phase 9

Only then consider Phase 10.

## V1 Exit Criteria

The boilerplate is ready for reuse when all of the following are true:

- The app shell is no longer starter-demo quality.
- Auth and account settings are stable and tested.
- RBAC is seeded, enforced, and tested.
- Users and roles management exists.
- Shared CRUD patterns are stable.
- Notifications and activity logs exist.
- API baseline exists.
- Setup and operations docs are complete.
