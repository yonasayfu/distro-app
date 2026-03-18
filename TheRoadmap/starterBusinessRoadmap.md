# `starter-business` Roadmap

## Goal

Build the second boilerplate level on top of `starter-core-v1`.

`starter-business` should add cross-project business foundations that many operational systems need, while still staying domain-neutral.

It should support future projects such as:

- ecommerce back office
- inventory management
- organization automation
- ERP-style internal systems
- HR/admin systems

It should not contain industry-specific business logic.

## Base Assumption

This level starts from:

- `starter-core-v1`

That means these are already present and should not be redesigned here:

- auth and Fortify flows
- RBAC
- admin shell
- users and roles
- shared CRUD patterns
- notifications and activity logs
- API baseline
- public landing and handbook
- public pages

## Scope Rule

Only add features here if they satisfy all of these:

- useful in many business applications
- not tied to one industry
- reusable across future modules
- can later support domain starters such as ERP, ecommerce, or HR

If a feature is procurement-only, payroll-only, stock-market-only, or warehouse-only, keep it out of `starter-business`.

## Recommended Phase Order

### Phase B0: Scope Lock

Goal:
Define the exact business-level boundary so this branch does not turn into a domain app.

Deliverables:

- scope and exclusions
- freeze criteria for `starter-business-v1`
- business-level task tracker

### Phase B1: Settings Foundation

Goal:
Add a reusable settings layer for application-wide and organization-wide configuration.

Recommended scope:

- app settings
- organization profile
- branding basics
- contact details
- public website shared settings
- feature flags placeholder structure later

Deliverables:

- settings model and storage pattern
- admin settings screens
- typed frontend settings props
- settings API baseline if useful

### Phase B2: Media and File Foundation

Goal:
Add a reusable upload and attachment pattern.

Recommended scope:

- file upload
- file metadata
- storage abstraction
- attachment-ready UI pattern
- attachment API/resource pattern

Deliverables:

- shared file model or attachment pattern
- upload endpoint and validation
- reusable file field component
- basic file list and download behavior

### Phase B3: Notes and Comments Layer

Goal:
Add a generic internal collaboration note pattern usable by many modules.

Recommended scope:

- notes/comments on records
- author and timestamp
- optional pin/highlight state later
- activity-log linkage where useful

Deliverables:

- polymorphic note model or equivalent reusable pattern
- shared notes UI panel
- permission-aware add/edit/delete behavior

### Phase B4: Status and Workflow Pattern

Goal:
Add a reusable state model for records that move through business processes.

Recommended scope:

- draft
- active
- archived
- pending
- approved
- rejected

Deliverables:

- shared status conventions
- UI badges and transitions
- policy and audit integration

### Phase B5: Import and Restore Foundations

Goal:
Add the data entry foundations most business systems need.

Recommended scope:

- CSV import baseline
- validation and preview workflow
- import result reporting
- soft delete and restore pattern

Deliverables:

- import service pattern
- import history or result summary
- restore flows for soft-deleted records

### Phase B6: Dashboard and Reporting Base

Goal:
Add reusable business-facing summary surfaces.

Recommended scope:

- stat cards
- recent activity widgets
- trend blocks
- filterable table reports

Deliverables:

- dashboard widget primitives
- reporting page pattern
- exportable report table baseline

## First Recommended Build Slice

Start here:

1. settings foundation
2. media/file foundation
3. notes/comments layer

Reason:

- these are the most reusable business-level features
- they help almost every app type you listed
- they do not force domain assumptions too early

## What Is Intentionally Deferred

Keep these out of `starter-business` for now:

- product catalog
- cart and checkout
- warehouse movement rules
- payroll
- attendance
- recruitment
- accounting ledger
- procurement workflows
- stock-market specific entities

Those belong in domain-level starters, not here.

## Success Condition

`starter-business-v1` is ready when:

- the shared business foundations are in place
- new projects can add domain modules on top of them without redesigning settings/files/workflow basics
- the branch still feels like a boilerplate, not like one specific product
