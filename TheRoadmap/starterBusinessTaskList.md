# `starter-business` Task List

Status keys:

- `[P0]` required for `starter-business-v1`
- `[P1]` important for `starter-business-v1`
- `[P2]` useful but can wait

## Phase B0: Scope Lock

- [x] `[P0]` Confirm `starter-business` starts from `starter-core-v1`.
- [x] `[P0]` Confirm domain exclusions so ERP/ecommerce/HR features stay out of this level.
- [x] `[P0]` Define the `starter-business-v1` freeze boundary.
- [x] `[P1]` Map each new feature to business-level reuse, not domain logic.

Acceptance criteria:

- the branch has a clean identity
- scope creep is explicitly blocked

## Phase B1: Settings Foundation

- [x] `[P0]` Define a reusable settings storage strategy.
- [x] `[P0]` Add application settings management.
- [x] `[P0]` Add organization profile settings.
- [x] `[P1]` Add public website shared settings such as contact/footer basics.
- [x] `[P1]` Add typed shared frontend settings props.
- [x] `[P1]` Add settings tests.

Acceptance criteria:

- app-wide and organization-wide configuration no longer requires hardcoded values

Implementation notes:

- shared settings now persist through the `settings` table instead of hardcoded config or page-local constants
- the first business-level settings scope covers application identity, organization profile, and public website copy
- `HandleInertiaRequests` now shares resolved settings values so the public layout and shell branding can read from the database
- `Admin > Settings` is now a real permission-aware module with policy checks, activity logging, and Pest coverage

## Phase B2: Media and File Foundation

- [x] `[P0]` Define the attachment/file model strategy.
- [x] `[P0]` Add validated upload endpoints.
- [x] `[P0]` Add reusable upload UI pattern.
- [x] `[P1]` Add file list/download behavior.
- [x] `[P1]` Add attachment-ready API resource pattern.
- [x] `[P1]` Add media tests.

Acceptance criteria:

- future modules can attach files without inventing a new pattern every time

Implementation notes:

- the shared `media` table now stores uploader metadata, storage location, collection name, and an optional polymorphic `attachable` target
- uploads are validated through a Form Request and persisted through one reusable `MediaUploader` service instead of page-local controller logic
- `Admin > Media` is now a real business-level library with upload, search, download, and delete flows
- the module is permission-aware and already shaped for future attachment reuse through the `Media` model and `MediaResource`

## Phase B3: Notes and Comments Layer

- [x] `[P0]` Add reusable notes/comments model pattern.
- [x] `[P0]` Add note create/list/delete behavior.
- [ ] `[P1]` Add note edit behavior if needed.
- [x] `[P1]` Add permission-aware note actions.
- [x] `[P1]` Add audit logging for note changes where appropriate.
- [x] `[P1]` Add notes tests.

Acceptance criteria:

- any future record can support internal notes through one shared pattern

Implementation notes:

- the shared `notes` table now uses a polymorphic `noteable` target so one note pattern can attach to users, pages, media, and future business records
- note creation and deletion flow through one generic controller and one reusable `NotesPanel` UI instead of per-module note endpoints
- notes are now embedded into the existing user and page edit screens so record-level context stays with the record
- note actions are governed by dedicated `notes.view`, `notes.create`, and `notes.delete` permissions and recorded in the activity log

## Phase B4: Status and Workflow Pattern

- [x] `[P0]` Define reusable status vocabulary and conventions.
- [x] `[P0]` Add status badge UI primitives.
- [x] `[P0]` Add status transition rules/pattern.
- [x] `[P1]` Add workflow-aware audit logging.
- [x] `[P1]` Add status tests.

Acceptance criteria:

- future modules can model business-state transitions consistently

Implementation notes:

- the first shared workflow implementation now lives on `Page` through a backed enum instead of only a page-specific publish checkbox
- status vocabulary is now explicit: `draft`, `review`, `published`, `archived`
- transition rules are enforced in one `WorkflowTransitionRegistry` so invalid status jumps fail validation instead of becoming controller conditionals
- `StatusBadge` now provides one reusable visual primitive for workflow state across future business modules

## Phase B5: Import and Restore Foundations

- [x] `[P0]` Add CSV import baseline.
- [x] `[P0]` Add import validation and preview pattern.
- [x] `[P1]` Add import result summary/history.
- [x] `[P0]` Add soft delete and restore conventions where appropriate.
- [x] `[P1]` Add import and restore tests.

Acceptance criteria:

- the boilerplate supports safe bulk intake and safe record recovery

Implementation notes:

- the import baseline now uses a reusable `ImportRun` history model instead of a preview-only session flow
- the first CSV intake path is implemented for `Page` records with upload, preview, confirm, and recent-run history
- `Page` now uses soft deletes and a restore route so destructive actions become recoverable by default
- the page index now exposes deleted-record filters and restore actions, which establishes the business-level recovery pattern for future modules

## Phase B6: Dashboard and Reporting Base

- [x] `[P1]` Add reusable stat card widgets.
- [x] `[P1]` Add recent activity widget pattern.
- [x] `[P1]` Add filterable report table pattern.
- [x] `[P1]` Add report export hook points.
- [x] `[P1]` Add dashboard/report tests.

Acceptance criteria:

- future business modules can expose metrics without inventing new widget structures each time

Implementation notes:

- the old static dashboard page now loads real business-level metrics, activity, and quick-link data through a dedicated controller instead of placeholder copy
- shared `StatCard` and `RecentActivityPanel` components now define the first reusable widget surface for later modules
- the first reporting page now supports search, workflow-status filters, deleted-record filters, and CSV export through one neutral report pattern
- report export actions are activity-logged so future report downloads follow the same audit trail pattern as other shared modules

## Phase B7: Release Readiness and Freeze

- [x] `[P0]` Confirm the business-level freeze scope is complete.
- [x] `[P0]` Update release docs for the business level.
- [x] `[P0]` Update tracker and learning archive.
- [x] `[P0]` Record the exact `starter-business-v1` verification and tag flow.

Acceptance criteria:

- the business level can be frozen without ambiguity
- the release/tag instructions are explicit

Implementation notes:

- `starter-business-v1` is now defined as an extension of `starter-core-v1`, not as a drifting branch label
- the README now documents both stable levels and their different verification baselines
- the business freeze docs now include the exact tag workflow and the release boundary for what stays out of this level

## First Implementation Slice

Start in this order:

- [x] `[P0]` Settings foundation
- [x] `[P0]` Media/file foundation
- [x] `[P0]` Notes/comments layer
- [x] `[P0]` Status and workflow pattern
- [x] `[P0]` Import and restore foundations

This first slice gives `starter-business` its identity without turning it into a domain app.
