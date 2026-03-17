# Boilerplate Task List

Status keys:

- `[P0]` critical for V1
- `[P1]` important for V1 quality
- `[P2]` useful but can wait

## Phase 0: Scope Freeze and Extraction

- [x] `[P0]` Confirm that the boilerplate is `users`-centric, not `staff`-centric.
- [x] `[P0]` Confirm the V1 scope and list the excluded asset-management modules.
- [x] `[P0]` Keep the Laravel default folder structure unless a change is justified.
- [x] `[P1]` Move reusable planning guidance from the deleted reference set into `TheRoadmap/`.
- [x] `[P1]` Create a short implementation decision log for future tradeoffs.
- [x] `[P1]` Keep `laravelbasics.md` updated after each meaningful implementation step.
- [x] `[P1]` Keep `mcpguidance.md` and `gitguidance.md` aligned with actual workflow decisions.

Acceptance criteria:

- Boilerplate boundaries are documented.
- There is one clear source of truth for what gets built.
- The learning and workflow docs are in place before feature implementation begins.

## Phase 1: App Shell and Design Foundation

- [x] `[P0]` Replace the current starter-style landing/dashboard feel with a neutral admin shell.
- [x] `[P0]` Create a single config-driven navigation source for sidebar and header usage.
- [x] `[P0]` Standardize breadcrumbs and page header structure.
- [x] `[P0]` Define reusable page container spacing rules.
- [x] `[P1]` Add shared empty states for list and detail pages.
- [x] `[P1]` Add shared loading or skeleton states.
- [x] `[P1]` Add shared error presentation pattern.
- [x] `[P1]` Standardize header quick actions.

Acceptance criteria:

- New pages can use one consistent shell without custom layout work.

## Phase 2: Auth, Account, and Security

- [ ] `[P0]` Keep login, register, logout, forgot password, reset password, and verify email flows.
- [ ] `[P0]` Keep two-factor authentication and recovery codes.
- [ ] `[P0]` Keep profile, password, and appearance settings.
- [ ] `[P1]` Review auth page styling and consistency with the new shell.
- [ ] `[P1]` Review Fortify rate limiting and route middleware usage.
- [ ] `[P0]` Add or update Pest coverage for login, password reset, 2FA, and settings flows.

Acceptance criteria:

- Core account management is stable and tested.

## Phase 3: RBAC Foundation

- [x] `[P0]` Install and configure Spatie Permission.
- [x] `[P0]` Create role and permission seeders.
- [x] `[P0]` Define a default role matrix for the boilerplate.
- [ ] `[P0]` Add policy conventions for admin modules.
- [x] `[P0]` Enforce permissions in routes and controllers.
- [x] `[P0]` Gate sidebar items by permission.
- [x] `[P0]` Add tests for unauthorized access and visible navigation rules.
- [x] `[P1]` Add helper props for frontend permission checks.

Acceptance criteria:

- Authorization is enforced at route, controller, and UI levels.

## Phase 4: RBAC Management and Role Experience

- [x] `[P0]` Seed dedicated demo accounts for each default role: `Admin`, `Manager`, `Member`, `ReadOnly`, and a guest-like restricted role if kept.
- [x] `[P0]` Create admin-only role management pages.
- [x] `[P0]` Create detailed permission assignment UI for each role.
- [x] `[P0]` Ensure role changes affect sidebar visibility dynamically.
- [x] `[P0]` Ensure role changes affect page access dynamically.
- [x] `[P0]` Ensure role changes affect CRUD actions dynamically.
- [ ] `[P1]` Ensure print and export actions are permission-aware.
- [ ] `[P1]` Add direct-permission support when a user needs exceptions beyond role defaults.
- [x] `[P0]` Establish the future-module rule: every new page, action, print, or export capability must map to a permission.
- [x] `[P0]` Add tests proving each role sees only its allowed routes and navigation.

Acceptance criteria:

- Each role has a testable seeded account.
- Admin can manage role access from the UI.
- Sidebar, pages, and actions change immediately based on assigned roles and permissions.
- Access control is not hardcoded per page outside the shared RBAC system.

Implementation notes:

- `Users` and `Roles` are now real admin modules backed by controllers, Form Requests, and Inertia pages.
- Role permissions can be updated from the UI, except for the protected `Admin` recovery role.
- User role assignments can be updated from the UI, with a guard that prevents the current admin from removing their own `Admin` role.
- Sidebar visibility remains permission-driven because the shared Inertia auth payload still reads the current user's effective permissions on every request.
- Notifications and activity-log placeholder pages now exist so `Admin`, `Manager`, `Member`, and restricted roles do not all see the same sidebar structure.

## Phase 5: Admin Core Modules

- [x] `[P0]` Build Users index page.
- [x] `[P0]` Build Users create page.
- [x] `[P0]` Build Users edit page.
- [ ] `[P1]` Build Users detail page if needed for audit and notification context.
- [x] `[P0]` Build Roles index page.
- [x] `[P0]` Build Roles create page.
- [x] `[P0]` Build Roles edit page.
- [x] `[P1]` Add role assignment inside user management.
- [x] `[P1]` Add permission assignment flow where appropriate.
- [x] `[P0]` Use Form Requests for all validation.
- [x] `[P0]` Add tests for CRUD and authorization.

Acceptance criteria:

- The boilerplate can manage users and access control without external setup.
- Index pages now use shared search, table, pagination, and destructive-action patterns instead of module-specific one-off markup.

## Phase 6: Shared CRUD Foundation

- [x] `[P0]` Create a reusable resource toolbar pattern.
- [x] `[P0]` Create a reusable list table pattern.
- [x] `[P0]` Standardize server-driven pagination usage.
- [x] `[P0]` Standardize search and filter layout.
- [x] `[P0]` Standardize action buttons as icons with accessible labels.
- [x] `[P0]` Standardize confirmation modal behavior for destructive actions.
- [x] `[P0]` Standardize toast or flash success and error messaging.
- [ ] `[P1]` Create reusable form section wrappers.
- [ ] `[P1]` Create reusable empty state and zero-state components.
- [ ] `[P1]` Add loading skeletons for list and form pages.
- [x] `[P1]` Document the CRUD page pattern in `followTemplate.md`.

Acceptance criteria:

- Future modules can follow one repeatable page structure.

## Phase 5.5: Early API Foundation

- [x] `[P0]` Install and configure Sanctum.
- [x] `[P0]` Create `/api/v1` route structure.
- [x] `[P0]` Add auth endpoints for login, logout, and current user.
- [x] `[P1]` Add one protected admin example endpoint for user management listing.
- [x] `[P0]` Standardize API unauthenticated and forbidden responses.
- [ ] `[P0]` Standardize API validation error format more explicitly if custom shaping is needed later.
- [ ] `[P1]` Add notification feed endpoint.
- [ ] `[P1]` Add broader admin summary endpoints.
- [x] `[P0]` Add Pest API smoke tests.

Acceptance criteria:

- Token-based API access works.
- The current-user endpoint returns RBAC context for other clients.
- At least one protected admin API route proves shared authorization behavior outside Inertia.

Implementation notes:

- This was intentionally implemented early so mobile or third-party clients do not need a second authorization model later.
- The current scope is baseline only, not a full public API for every module.

## Phase 7: Notifications and Activity Logs

- [x] `[P0]` Add database notification support and UI entry points.
- [x] `[P0]` Build notification bell with unread count.
- [x] `[P0]` Build notifications index page.
- [x] `[P1]` Add mark-as-read and mark-all-read actions.
- [x] `[P0]` Add activity logging for auth, user, role, and settings changes.
- [x] `[P0]` Build audit log index page with filters.
- [x] `[P1]` Add audit log entry detail drawer or detail page.
- [x] `[P1]` Decide what events must always be recorded.
- [x] `[P0]` Add tests for notifications and activity logging flows.

Acceptance criteria:

- Cross-cutting operational visibility exists in the base boilerplate.

Implementation notes:

- Database notifications now power the in-app notification center.
- The header bell now includes a live preview dropdown and empty state from shared Inertia props.
- Activity logs now capture auth, notification, admin user/role, and settings actions.
- The audit page supports event and text filtering with server-side pagination.
- Audit entries now have a dedicated detail page for payload inspection.

## Phase 8: Expanded API Baseline

- [x] `[P0]` Install and configure Sanctum.
- [x] `[P0]` Create `/api/v1` route structure.
- [x] `[P0]` Add auth endpoints for login, logout, and current user.
- [x] `[P1]` Add notification feed endpoint.
- [x] `[P1]` Add admin summary or user-management summary endpoint.
- [x] `[P0]` Standardize API error format for unauthenticated and forbidden responses.
- [x] `[P0]` Standardize API pagination format more formally across future endpoints.
- [x] `[P1]` Add OpenAPI or Postman artifacts.
- [x] `[P0]` Add Pest API smoke tests.

Acceptance criteria:

- The API can serve as the base for mobile or third-party consumers.

Implementation notes:

- Notifications now expose list, detail, mark-read, and mark-all-read API endpoints.
- Activity logs now expose list and detail API endpoints with the same permission model as the web UI.
- Auth, notifications, and audit flows now share the same event and permission backbone across web and API clients.
- Paginated API endpoints now use one explicit `data`, `links`, and `meta.pagination` response shape.
- The admin summary endpoint now exposes counts, recent users, and role breakdown data for external admin dashboards.
- A Postman collection now exists at the repository root for quick API import and manual testing.

## Phase 9: Developer Experience and Operations

- [x] `[P0]` Write a real project README.
- [x] `[P0]` Write local setup instructions.
- [x] `[P1]` Write mail, queue, scheduler, and deployment notes.
- [x] `[P1]` Review `.env.example` for missing defaults.
- [x] `[P0]` Add CI workflow for lint, types, tests, and build.
- [ ] `[P1]` Add devcontainer or docker support if desired.
- [ ] `[P1]` Add release or boilerplate snapshot guidance.

Acceptance criteria:

- A new clone is easy to install and validate.

Implementation notes:

- A real root `README.md` now documents stack, setup, seeded accounts, API usage, CI, and operations notes.
- `.env.example` now includes clearer boilerplate naming, PostgreSQL guidance, and Sanctum stateful domain defaults.
- GitHub Actions now run non-destructive formatting checks, ESLint, type checks, build verification, and the test suite.
- `composer setup` now matches the documented local bootstrap path more closely.

## Phase 10: Optional Extensions

- [ ] `[P2]` Impersonation
- [x] `[P2]` Export and print foundation
- [x] `[P2]` Global search
- [x] `[P2]` Internal handbook and learning center
- [ ] `[P2]` Localization scaffold
- [ ] `[P2]` Observability baseline
- [ ] `[P2]` Feature toggles
- [ ] `[P2]` Messaging and task modules
- [ ] `[P2]` Mailpit mailbox ingestion

Acceptance criteria:

- Optional features stay optional and do not distort the base starter.

## Phase 12: Public Website Foundation

- [x] `[P1]` Create a public layout separate from the authenticated app shell.
- [x] `[P1]` Build a polished public landing page with hero, feature, CTA, and footer sections.
- [x] `[P1]` Add guest navigation and mobile navigation behavior.
- [ ] `[P1]` Add a `pages` content model for admin-managed public pages.
- [ ] `[P1]` Build admin CRUD for public pages.
- [ ] `[P1]` Add slug-based public page routes.
- [ ] `[P1]` Add publish/draft support so unpublished content stays private.
- [ ] `[P1]` Add basic SEO fields for public content.
- [ ] `[P2]` Add a `posts` or `updates` module for repeatable public publishing.
- [ ] `[P2]` Add site settings management for shared public content like footer, CTA, and social links.
- [ ] `[P1]` Add tests for public visibility, draft protection, and admin content management.

Acceptance criteria:

- The project supports a real guest-facing website as well as the private admin application.
- Public pages are backend-managed instead of hardcoded.
- Draft content is never shown publicly.
- Public and private layouts are intentionally separate.

## Levels Strategy

- [x] `[P1]` Define the boilerplate levels strategy: `starter-core`, `starter-business`, and `starter-enterprise`.
- [x] `[P1]` Map the current repository to `starter-core`.
- [ ] `[P1]` Freeze the remaining `starter-core` scope before branching into `starter-business`.
- [ ] `[P1]` Create the branch/tag release plan for `starter-core-v1`.

Acceptance criteria:

- The current repo has a clear level identity.
- Future features can be placed into the correct level before implementation starts.

Implementation notes:

- Global search now exists as a permission-aware cross-module search page.
- Export center now exists as a reusable surface for future CSV, PDF, and print actions.
- The first working print/export examples are users CSV download and a print-friendly workspace summary page.

## First Implementation Slice

If implementation starts immediately, do this first:

- [ ] `[P0]` Finalize scope decision and identity model.
- [ ] `[P0]` Upgrade the app shell and navigation.
- [ ] `[P0]` Add RBAC.
- [ ] `[P0]` Build RBAC management and Users/Roles modules.
- [ ] `[P0]` Lock in reusable CRUD patterns.

This first slice gives the boilerplate its real shape. Everything after that becomes much easier to add cleanly.
