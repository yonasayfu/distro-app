# `starter-core-v1` Freeze Plan

## Goal

Define the exact release boundary for the first stable `starter-core` tag.

This document answers one question:

- what must be true before this repository can be tagged as `starter-core-v1`

It also answers the opposite question:

- what should not be added before the first `starter-core` release

## What `starter-core` means

`starter-core` is the clean, domain-neutral Laravel boilerplate layer.

It should solve:

- authentication
- authorization
- admin shell
- user and role management
- shared CRUD patterns
- notifications
- activity logs
- API baseline
- deployment and setup baseline
- public landing and handbook

It should not solve domain business logic.

## What is already complete

The current repository already includes these completed areas:

- app shell and grouped navigation
- Fortify auth flows and account settings baseline
- RBAC foundation with seeded roles and demo users
- admin `Users` and `Roles` modules
- shared CRUD toolbar, table, pagination, confirmation, and flash patterns
- notification center
- activity log index and detail pages
- versioned Sanctum API baseline
- Postman collection and CI baseline
- public landing page
- handbook/learning center
- roadmap and workflow documentation

## What must still be completed before `starter-core-v1`

These are the remaining freeze items.

### Auth and security review status

This closeout item is now complete.

Completed:

- confirmed login, register, logout, forgot password, reset password, verify email, and 2FA remain in scope
- reviewed auth layout consistency against the newer public/app surfaces
- reviewed Fortify route registration, middleware usage, and rate limiting
- refreshed auth and settings feature coverage

Why it matters:

- the starter's authentication layer is now explicitly reviewed instead of only assumed to work
- the tracker now reflects the real implementation state

### Policy conventions status

This closeout item is now complete.

Completed:

- `UserPolicy`, `RolePolicy`, and `PagePolicy` now exist as reference examples
- admin controllers now call explicit policy checks through `$this->authorize(...)`
- policy registration is explicit in the application service provider
- the convention is now documented in the tracker and learning archive

Why it matters:

- future modules no longer need to guess whether authorization belongs only in routes
- `starter-core` now demonstrates the intended pattern: route permission plus controller policy

### Shared CRUD foundation status

This closeout item is now complete.

Completed:

- reusable form section wrapper
- empty-state and zero-state review
- loading skeletons for list and form pages

Why it matters:

- `starter-core` now exposes a more complete CRUD UI kit instead of mostly-repeated markup
- future modules can follow the same form/list shape with less custom page structure

### Public pages minimum cut status

This closeout item is now complete.

Completed:

- `pages` content model
- admin CRUD for public pages
- slug-based public page routes
- publish/draft protection
- basic SEO fields
- focused tests for public visibility and draft protection

Why it matters:

- the public side is no longer limited to hardcoded marketing content
- `starter-core` now demonstrates one backend-managed public content flow before branching into `starter-business`

### 1. Release readiness checklist

This closeout item is now complete.

Completed:

- reviewed `.env.example` and aligned the default app identity with `starter-core`
- confirmed README install, Herd, deployment, and seeded-account guidance matches the actual repository
- confirmed CI commands still match the current Composer, npm, and test workflow
- added explicit tag and release instructions to `gitguidance.md`
- aligned Composer package metadata with the repository's starter identity

Why it matters:

- a boilerplate release should be installable and taggable from docs without tribal knowledge

## Remaining freeze focus

The biggest remaining `starter-core-v1` items are now:

- none

## What is intentionally deferred to `starter-business`

Do not block `starter-core-v1` on these items:

- generic settings system
- media/file foundation
- notes/comments layer
- status/workflow foundation
- import foundation
- dashboard widget library
- broader reporting patterns
- soft-delete and restore conventions

These belong to `starter-business`, not `starter-core`.

## What is intentionally deferred to `starter-enterprise`

Do not block `starter-core-v1` on these items:

- approval workflow foundation
- advanced audit depth
- localization scaffold
- organization profile layer
- currency/date/number helpers
- stronger reporting architecture
- queue-heavy operational patterns
- tenancy-ready constraints

These belong to `starter-enterprise`.

## Freeze decision rule

A feature may still enter `starter-core-v1` only if all of the following are true:

- it is useful in almost every project
- it does not introduce domain coupling
- it is not substantially easier to add in `starter-business`
- it does not delay the first stable release unnecessarily

If any of those fail, defer it.

## Proposed release sequence

1. close the remaining freeze items
2. mark the tracker items complete
3. run the final targeted test pass
4. create and push the tag `starter-core-v1`
5. branch to `level/starter-business`

## Recommended release statement

When this freeze is complete, the repository should be described as:

`starter-core-v1` is a reusable Laravel 12 + Inertia + Vue boilerplate with auth, RBAC, admin CRUD conventions, notifications, audit logging, API baseline, public landing, and handbook support.

## Success condition

`starter-core-v1` is ready when:

- core platform features are complete
- the remaining open tracker items are either finished or explicitly deferred
- a new project can clone the repository and understand what belongs in the base versus higher starter levels
- release documentation, environment defaults, and tag workflow all match the actual codebase
