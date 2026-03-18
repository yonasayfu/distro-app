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

- [ ] `[P0]` Add reusable notes/comments model pattern.
- [ ] `[P0]` Add note create/list/delete behavior.
- [ ] `[P1]` Add note edit behavior if needed.
- [ ] `[P1]` Add permission-aware note actions.
- [ ] `[P1]` Add audit logging for note changes where appropriate.
- [ ] `[P1]` Add notes tests.

Acceptance criteria:

- any future record can support internal notes through one shared pattern

## Phase B4: Status and Workflow Pattern

- [ ] `[P0]` Define reusable status vocabulary and conventions.
- [ ] `[P0]` Add status badge UI primitives.
- [ ] `[P0]` Add status transition rules/pattern.
- [ ] `[P1]` Add workflow-aware audit logging.
- [ ] `[P1]` Add status tests.

Acceptance criteria:

- future modules can model business-state transitions consistently

## Phase B5: Import and Restore Foundations

- [ ] `[P0]` Add CSV import baseline.
- [ ] `[P0]` Add import validation and preview pattern.
- [ ] `[P1]` Add import result summary/history.
- [ ] `[P0]` Add soft delete and restore conventions where appropriate.
- [ ] `[P1]` Add import and restore tests.

Acceptance criteria:

- the boilerplate supports safe bulk intake and safe record recovery

## Phase B6: Dashboard and Reporting Base

- [ ] `[P1]` Add reusable stat card widgets.
- [ ] `[P1]` Add recent activity widget pattern.
- [ ] `[P1]` Add filterable report table pattern.
- [ ] `[P1]` Add report export hook points.
- [ ] `[P1]` Add dashboard/report tests.

Acceptance criteria:

- future business modules can expose metrics without inventing new widget structures each time

## First Implementation Slice

Start in this order:

- [x] `[P0]` Settings foundation
- [x] `[P0]` Media/file foundation
- [ ] `[P0]` Notes/comments layer

This first slice gives `starter-business` its identity without turning it into a domain app.
