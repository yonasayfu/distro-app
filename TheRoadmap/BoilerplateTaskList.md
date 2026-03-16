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
- [ ] `[P0]` Enforce permissions in routes and controllers.
- [x] `[P0]` Gate sidebar items by permission.
- [ ] `[P0]` Add tests for unauthorized access and visible navigation rules.
- [x] `[P1]` Add helper props for frontend permission checks.

Acceptance criteria:

- Authorization is enforced at route, controller, and UI levels.

## Phase 4: RBAC Management and Role Experience

- [x] `[P0]` Seed dedicated demo accounts for each default role: `Admin`, `Manager`, `Member`, `ReadOnly`, and a guest-like restricted role if kept.
- [ ] `[P0]` Create admin-only role management pages.
- [ ] `[P0]` Create detailed permission assignment UI for each role.
- [ ] `[P0]` Ensure role changes affect sidebar visibility dynamically.
- [ ] `[P0]` Ensure role changes affect page access dynamically.
- [ ] `[P0]` Ensure role changes affect CRUD actions dynamically.
- [ ] `[P1]` Ensure print and export actions are permission-aware.
- [ ] `[P1]` Add direct-permission support when a user needs exceptions beyond role defaults.
- [ ] `[P0]` Establish the future-module rule: every new page, action, print, or export capability must map to a permission.
- [ ] `[P0]` Add tests proving each role sees only its allowed routes and navigation.

Acceptance criteria:

- Each role has a testable seeded account.
- Admin can manage role access from the UI.
- Sidebar, pages, and actions change immediately based on assigned roles and permissions.
- Access control is not hardcoded per page outside the shared RBAC system.

Implementation notes:

- Admin-only placeholder pages for `Users` and `Roles` now exist as the first visible proof of RBAC-driven navigation and route protection.

## Phase 5: Admin Core Modules

- [ ] `[P0]` Build Users index page.
- [ ] `[P0]` Build Users create page.
- [ ] `[P0]` Build Users edit page.
- [ ] `[P1]` Build Users detail page if needed for audit and notification context.
- [ ] `[P0]` Build Roles index page.
- [ ] `[P0]` Build Roles create page.
- [ ] `[P0]` Build Roles edit page.
- [ ] `[P1]` Add role assignment inside user management.
- [ ] `[P1]` Add permission assignment flow where appropriate.
- [ ] `[P0]` Use Form Requests for all validation.
- [ ] `[P0]` Add tests for CRUD and authorization.

Acceptance criteria:

- The boilerplate can manage users and access control without external setup.

## Phase 6: Shared CRUD Foundation

- [ ] `[P0]` Create a reusable resource toolbar pattern.
- [ ] `[P0]` Create a reusable list table pattern.
- [ ] `[P0]` Standardize server-driven pagination usage.
- [ ] `[P0]` Standardize search and filter layout.
- [ ] `[P0]` Standardize action buttons as icons with accessible labels.
- [ ] `[P0]` Standardize confirmation modal behavior for destructive actions.
- [ ] `[P0]` Standardize toast or flash success and error messaging.
- [ ] `[P1]` Create reusable form section wrappers.
- [ ] `[P1]` Create reusable empty state and zero-state components.
- [ ] `[P1]` Add loading skeletons for list and form pages.
- [ ] `[P1]` Document the CRUD page pattern in `followTemplate.md`.

Acceptance criteria:

- Future modules can follow one repeatable page structure.

## Phase 7: Notifications and Activity Logs

- [ ] `[P0]` Add database notification support and UI entry points.
- [ ] `[P0]` Build notification bell with unread count.
- [ ] `[P0]` Build notifications index page.
- [ ] `[P1]` Add mark-as-read and mark-all-read actions.
- [ ] `[P0]` Add activity logging for auth, user, role, and settings changes.
- [ ] `[P0]` Build audit log index page with filters.
- [ ] `[P1]` Add audit log entry detail drawer or detail page.
- [ ] `[P1]` Decide what events must always be recorded.
- [ ] `[P0]` Add tests for notifications and activity logging flows.

Acceptance criteria:

- Cross-cutting operational visibility exists in the base boilerplate.

## Phase 8: API Baseline

- [ ] `[P0]` Install and configure Sanctum.
- [ ] `[P0]` Create `/api/v1` route structure.
- [ ] `[P0]` Add auth endpoints for login, logout, and current user.
- [ ] `[P1]` Add notification feed endpoint.
- [ ] `[P1]` Add admin summary or user-management summary endpoint.
- [ ] `[P0]` Standardize API error format.
- [ ] `[P0]` Standardize API pagination format.
- [ ] `[P1]` Add OpenAPI or Postman artifacts.
- [ ] `[P0]` Add Pest API smoke tests.

Acceptance criteria:

- The API can serve as the base for mobile or third-party consumers.

## Phase 9: Developer Experience and Operations

- [ ] `[P0]` Write a real project README.
- [ ] `[P0]` Write local setup instructions.
- [ ] `[P1]` Write mail, queue, scheduler, and deployment notes.
- [ ] `[P1]` Review `.env.example` for missing defaults.
- [ ] `[P0]` Add CI workflow for lint, types, tests, and build.
- [ ] `[P1]` Add devcontainer or docker support if desired.
- [ ] `[P1]` Add release or boilerplate snapshot guidance.

Acceptance criteria:

- A new clone is easy to install and validate.

## Phase 10: Optional Extensions

- [ ] `[P2]` Impersonation
- [ ] `[P2]` Export and print foundation
- [ ] `[P2]` Global search
- [ ] `[P2]` Localization scaffold
- [ ] `[P2]` Observability baseline
- [ ] `[P2]` Feature toggles
- [ ] `[P2]` Messaging and task modules
- [ ] `[P2]` Mailpit mailbox ingestion

Acceptance criteria:

- Optional features stay optional and do not distort the base starter.

## First Implementation Slice

If implementation starts immediately, do this first:

- [ ] `[P0]` Finalize scope decision and identity model.
- [ ] `[P0]` Upgrade the app shell and navigation.
- [ ] `[P0]` Add RBAC.
- [ ] `[P0]` Build RBAC management and Users/Roles modules.
- [ ] `[P0]` Lock in reusable CRUD patterns.

This first slice gives the boilerplate its real shape. Everything after that becomes much easier to add cleanly.
