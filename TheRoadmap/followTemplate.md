# followTemplate

This file is the reusable implementation guide extracted from the reference materials. It exists so the useful UI and development patterns survive even after `Refernce/` is deleted.

Use this file later as the default template when building pages, components, modules, and admin flows in this repo.

## Core Rule

Do not copy domain-specific features into the boilerplate. Copy only the reusable shell, UX patterns, feedback patterns, and architecture conventions.

## 1. Layout Pattern

Use a consistent application shell:

- Sidebar on the left
- Header with page context and quick actions
- Breadcrumbs near the top
- Main content area with stable spacing
- Auth layout separate from the dashboard layout

Guidance:

- Navigation should be config-driven, not hardcoded in multiple places.
- Parent navigation should control child visibility.
- Layout should support both desktop and mobile without redesigning each page.
- Settings pages should live under a dedicated settings layout.

## 2. Sidebar Pattern

Use grouped navigation, not a flat list.

Expected groups for the boilerplate:

- Dashboard
- Administration
- Notifications
- Activity Logs
- Settings
- API or Developer tools later if needed

Rules:

- Hide items the current user cannot access.
- Keep icons consistent across siblings.
- Use one central source of truth for labels, icons, routes, and permission gates.

## 3. Header Pattern

The header should always support:

- Breadcrumbs
- Page title and optional description
- Primary action button when relevant
- Quick actions such as theme toggle, user menu, and impersonation exit later if implemented

Do not make each page invent its own header layout.

## 4. CRUD Index Pattern

Every admin index page should follow one pattern:

1. Page header or resource toolbar
2. Search and filters
3. Table or list wrapper
4. Row actions on the right
5. Pagination at the bottom
6. Empty state when no records exist

Rules:

- Prefer icon-only row actions for view, edit, delete, and similar operations.
- Every icon-only action must still have accessible text through `title` and screen-reader labels.
- Keep filters aligned and consistent in size.
- Support server-side pagination and preserve query state.
- Add clear-filters behavior where filters exist.

## 5. CRUD Form Pattern

Every create and edit page should follow one structure:

- Page title and context
- Main form inside a reusable card or section wrapper
- Inputs grouped by meaning, not by random field order
- Validation errors shown inline
- Primary submit action at the bottom or top-right
- Cancel/back action present and predictable

Rules:

- Use Form Requests on the backend.
- Keep form labels, help text, and input spacing consistent.
- Do not mix different button styles inside the same form page.
- If a form has advanced sections, split them visually instead of creating a long unstructured block.

## 6. Detail Page Pattern

When a module needs a show page:

- Use a summary header
- Add action buttons near the title
- Use sections or tabs for related data
- Surface change history or activity when useful
- Keep related resources linked, not buried

If a detail page becomes important over time, it should become the anchor page for audit history, notifications, and future related actions.

## 7. Buttons and Action Pattern

Keep actions visually predictable:

- Primary action: solid or strongest emphasis
- Secondary action: ghost or neutral
- Destructive action: clearly dangerous
- Row actions: compact icon style

Rules:

- Do not mix text buttons and icon buttons for the same action type across modules.
- Destructive actions should require confirmation.
- Success and error feedback should appear immediately after action completion.

## 8. Feedback Pattern

Use the same feedback system everywhere:

- Success toasts or flash messages
- Inline validation errors
- Confirmation modal for destructive actions
- Clear empty states
- Loading skeletons for slower pages

Rules:

- Do not silently fail.
- Do not rely only on console logs.
- Errors should be staff-friendly and short.

## 9. Notifications Pattern

The reusable notification system should include:

- Notification bell in the header
- Unread badge
- Dropdown or preview list
- Full notifications page
- Mark one as read
- Mark all as read

Rules:

- Database notifications are the default baseline.
- Email is a channel, not the only notification experience.
- Notifications should be generic and reusable, not asset-specific.

Current baseline now implemented:

- Header bell with unread count
- Header bell dropdown preview
- Header bell empty state when nothing has been sent yet
- Full notifications page
- Mark one as read
- Mark all as read
- Notification payload fields:
  - `title`
  - `message`
  - `action_url`
  - `action_label`
  - `level`

## 10. Activity Logging Pattern

The activity log is not just a database feature. It must also have a readable admin UI.

Log at least:

- Auth events when appropriate
- User CRUD
- Role and permission changes
- Important settings changes
- Later, important domain events in downstream projects

Rules:

- Define the activity matrix before implementation grows.
- Keep event naming consistent.
- Make logs useful for support and debugging, not just technically present.

Current baseline now implemented:

- Audit list page
- Audit detail page
- `users.created`
- `users.updated`
- `users.roles-updated`
- `users.deleted`
- `roles.created`
- `roles.updated`
- `roles.permissions-updated`
- `roles.deleted`
- `auth.login`
- `auth.logout`
- `auth.api-login`
- `auth.api-logout`
- `settings.profile-updated`
- `settings.profile-deleted`
- `settings.password-updated`
- `notifications.read`
- `notifications.read-all`

Recommended event naming rule:

- use `module.action`
- keep event keys stable once clients or reports depend on them

## 11. Auth and Settings Pattern

The current repo already contains a useful base:

- Login
- Register
- Forgot password
- Reset password
- Verify email
- Two-factor authentication
- Profile
- Security
- Appearance

When improving these pages:

- Keep them clean and product-neutral.
- Match the same design language as the dashboard shell.
- Keep 2FA and recovery flows first-class, not hidden.

## 12. API Pattern

When API work begins, follow these defaults:

- Prefix routes with `/api/v1`
- Use Sanctum
- Standardize error envelope
- Standardize pagination shape
- Use resources or transformers consistently
- Document endpoints as they are added

The base API should start small:

- Auth
- Current user
- Notifications
- Small admin summary endpoints if needed

## 13. Developer Experience Pattern

The boilerplate should feel easy to adopt:

- Clear README
- Clear environment defaults
- Mail, queue, scheduler, and cache guidance
- CI checks for lint, types, tests, and build
- Optional devcontainer or docker support

Do not treat docs as an afterthought. For a boilerplate, docs are part of the product.

## 14. Naming and Scope Pattern

Rules:

- Use generic names
- Avoid previous project branding
- Avoid domain-heavy folder names in the base starter
- Prefer `users`, `roles`, `permissions`, `notifications`, `activity logs`, `settings`
- Add domain-specific tables and modules only in downstream projects

## 15. What to Reuse Later

These are the reusable ideas repeatedly seen in the reference docs:

- Config-driven sidebar
- Shared resource toolbar
- Consistent CRUD page anatomy
- Notification bell and notifications page
- Activity log matrix and admin view
- Icon-based row actions
- Confirmation and toast pattern
- Print and export as optional later modules
- API readiness as a first-class concern
- Operations docs, not just code

## 16. What Not to Copy into the Boilerplate

Do not pull these into V1:

- Assets and asset lifecycle flows
- Sites, locations, departments, and categories unless a specific project needs them
- Vendors, contracts, purchase orders, software
- Freshservice-like procurement features
- Audit workflow screens
- Asset-specific reports and dashboards
- Mailbox ingestion
- Collaboration modules

## 17. Source Intent

This file was distilled mainly from these reference ideas:

- Boilerplate feature planning
- Geraye migration planning
- CRUD implementation guidance
- Notification and email notes
- RBAC guidance
- Operations guidance

It is intentionally shorter and more reusable than the original reference set.
